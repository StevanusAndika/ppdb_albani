<?php

namespace App\Http\Controllers\PriceSetting;

use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Models\Price;
use Illuminate\Http\Request;

class PriceController extends Controller
{
    public function index(Package $package)
    {
        $prices = $package->prices()->orderBy('order')->get();
        return view('dashboard.admin.billing.prices.index', compact('package', 'prices'));
    }

    public function create(Package $package)
    {
        return view('dashboard.admin.billing.prices.create', compact('package'));
    }

    public function store(Request $request, Package $package)
    {
        $validated = $request->validate([
            'item_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'amount' => 'required|numeric|min:0',
        ]);

        try {
            $package->prices()->create($validated);
            return redirect()->route('admin.billing.packages.prices.index', $package)
                ->with('success', 'Biaya berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menambahkan biaya.')
                ->withInput();
        }
    }

    public function edit(Package $package, Price $price)
    {
        return view('dashboard.admin.billing.prices.edit', compact('package', 'price'));
    }

    public function update(Request $request, Package $package, Price $price)
    {
        $validated = $request->validate([
            'item_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'amount' => 'required|numeric|min:0',
        ]);

        try {
            $price->update($validated);
            return redirect()->route('admin.billing.packages.prices.index', $package)
                ->with('success', 'Biaya berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat memperbarui biaya.')
                ->withInput();
        }
    }

    public function destroy(Package $package, Price $price)
    {
        try {
            $price->delete();

            // Reorder remaining prices
            $remainingPrices = $package->prices()->orderBy('order')->get();
            foreach ($remainingPrices as $index => $remainingPrice) {
                $remainingPrice->update(['order' => $index + 1]);
            }

            return redirect()->route('admin.billing.packages.prices.index', $package)
                ->with('success', 'Biaya berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menghapus biaya.');
        }
    }

    public function toggleStatus(Package $package, Price $price, Request $request)
    {
        try {
            $isActive = $request->has('is_active') ? filter_var($request->is_active, FILTER_VALIDATE_BOOLEAN) : !$price->is_active;

            $price->update(['is_active' => $isActive]);

            $status = $isActive ? 'diaktifkan' : 'dinonaktifkan';

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => "Biaya berhasil $status!",
                    'is_active' => $price->is_active
                ]);
            }

            return redirect()->back()
                ->with('success', "Biaya berhasil $status!");
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat mengubah status biaya.'
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat mengubah status biaya.');
        }
    }
}

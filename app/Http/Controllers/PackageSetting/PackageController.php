<?php

namespace App\Http\Controllers\PackageSetting;

use App\Http\Controllers\Controller;
use App\Models\Package;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    public function index()
    {
        $packages = Package::with('activePrices')->get();
        return view('dashboard.admin.billing.packages.index', compact('packages'));
    }

    public function create()
    {
        return view('dashboard.admin.billing.packages.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'required_documents' => 'nullable|array',
            'required_documents.*' => 'nullable|string',
            'perlu_verifikasi' => 'required|in:yes,no',
            'is_active' => 'boolean',
        ]);

        // Normalize required documents: trim values and drop empty inputs
        $documents = collect($request->input('required_documents', []))
            ->map(fn ($doc) => is_string($doc) ? trim($doc) : $doc)
            ->filter(fn ($doc) => filled($doc));
        $validated['required_documents'] = $documents->isEmpty() ? null : $documents->values()->all();

        try {
            Package::create($validated);
            return redirect()->route('admin.billing.packages.index')
                ->with('success', 'Paket berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menambahkan paket.')
                ->withInput();
        }
    }

    public function edit(Package $package)
    {
        return view('dashboard.admin.billing.packages.edit', compact('package'));
    }

    public function update(Request $request, Package $package)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'required_documents' => 'nullable|array',
            'required_documents.*' => 'nullable|string',
            'perlu_verifikasi' => 'required|in:yes,no',
            'is_active' => 'boolean',
        ]);

        // Normalize required documents: trim values and drop empty inputs
        $documents = collect($request->input('required_documents', []))
            ->map(fn ($doc) => is_string($doc) ? trim($doc) : $doc)
            ->filter(fn ($doc) => filled($doc));
        $validated['required_documents'] = $documents->isEmpty() ? null : $documents->values()->all();

        try {
            $package->update($validated);
            return redirect()->route('admin.billing.packages.index')
                ->with('success', 'Paket berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat memperbarui paket.')
                ->withInput();
        }
    }

public function destroy(Package $package)
{
    try {
        // Hapus semua prices terkait terlebih dahulu
        $package->prices()->delete();

        // Hapus package
        $package->delete();

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Paket berhasil dihapus!'
            ]);
        }

        return redirect()->route('admin.billing.packages.index')
            ->with('success', 'Paket berhasil dihapus!');
    } catch (\Exception $e) {
        if (request()->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus paket.'
            ], 500);
        }

        return redirect()->back()
            ->with('error', 'Terjadi kesalahan saat menghapus paket.');
    }
}

    public function toggleStatus(Package $package, Request $request)
    {
        try {
            $isActive = $request->has('is_active') ? filter_var($request->is_active, FILTER_VALIDATE_BOOLEAN) : !$package->is_active;

            $package->update(['is_active' => $isActive]);

            $status = $isActive ? 'diaktifkan' : 'dinonaktifkan';

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => "Paket berhasil $status!",
                    'is_active' => $package->is_active
                ]);
            }

            return redirect()->back()
                ->with('success', "Paket berhasil $status!");
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat mengubah status paket.'
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat mengubah status paket.');
        }
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BillingMaster;
use App\Models\BillingItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BillingPackageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $packages = BillingMaster::with('items')->latest()->get();
        return view('admin.billing-packages.index', compact('packages'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.billing-packages.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'items' => 'required|array|min:1',
            'items.*.item_name' => 'required|string|max:255',
            'items.*.description' => 'nullable|string',
            'items.*.amount' => 'required|numeric|min:0',
            'items.*.quantity' => 'required|integer|min:1'
        ]);

        DB::transaction(function () use ($validated) {
            // Create package
            $package = BillingMaster::create([
                'name' => $validated['name'],
                'description' => $validated['description'],
                'is_active' => $validated['is_active'] ?? true,
                'total_amount' => 0
            ]);

            // Create items
            $totalAmount = 0;
            foreach ($validated['items'] as $itemData) {
                $item = $package->items()->create([
                    'item_name' => $itemData['item_name'],
                    'description' => $itemData['description'],
                    'amount' => $itemData['amount'],
                    'quantity' => $itemData['quantity']
                ]);
                $totalAmount += $item->amount * $item->quantity;
            }

            // Update total amount
            $package->update(['total_amount' => $totalAmount]);
        });

        return redirect()->route('admin.billing-packages.index')
            ->with('success', 'Paket billing berhasil dibuat!');
    }

    /**
     * Display the specified resource.
     */
    public function show(BillingMaster $billingPackage)
    {
        $billingPackage->load('items');
        return view('admin.billing-packages.show', compact('billingPackage'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BillingMaster $billingPackage)
    {
        $billingPackage->load('items');
        return view('admin.billing-packages.edit', compact('billingPackage'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BillingMaster $billingPackage)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'items' => 'required|array|min:1',
            'items.*.id' => 'sometimes|exists:billing_items,id',
            'items.*.item_name' => 'required|string|max:255',
            'items.*.description' => 'nullable|string',
            'items.*.amount' => 'required|numeric|min:0',
            'items.*.quantity' => 'required|integer|min:1'
        ]);

        DB::transaction(function () use ($validated, $billingPackage) {
            // Update package
            $billingPackage->update([
                'name' => $validated['name'],
                'description' => $validated['description'],
                'is_active' => $validated['is_active'] ?? true
            ]);

            // Sync items
            $existingItemIds = [];
            $totalAmount = 0;

            foreach ($validated['items'] as $itemData) {
                if (isset($itemData['id'])) {
                    // Update existing item
                    $item = BillingItem::where('id', $itemData['id'])
                        ->where('billing_master_id', $billingPackage->id)
                        ->first();

                    if ($item) {
                        $item->update([
                            'item_name' => $itemData['item_name'],
                            'description' => $itemData['description'],
                            'amount' => $itemData['amount'],
                            'quantity' => $itemData['quantity']
                        ]);
                        $existingItemIds[] = $item->id;
                        $totalAmount += $item->amount * $item->quantity;
                    }
                } else {
                    // Create new item
                    $item = $billingPackage->items()->create([
                        'item_name' => $itemData['item_name'],
                        'description' => $itemData['description'],
                        'amount' => $itemData['amount'],
                        'quantity' => $itemData['quantity']
                    ]);
                    $existingItemIds[] = $item->id;
                    $totalAmount += $item->amount * $item->quantity;
                }
            }

            // Delete removed items
            $billingPackage->items()
                ->whereNotIn('id', $existingItemIds)
                ->delete();

            // Update total amount
            $billingPackage->update(['total_amount' => $totalAmount]);
        });

        return redirect()->route('admin.billing-packages.index')
            ->with('success', 'Paket billing berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BillingMaster $billingPackage)
    {
        DB::transaction(function () use ($billingPackage) {
            $billingPackage->items()->delete();
            $billingPackage->delete();
        });

        return redirect()->route('admin.billing-packages.index')
            ->with('success', 'Paket billing berhasil dihapus!');
    }

    /**
     * Toggle status paket
     */
    public function toggleStatus(BillingMaster $billingPackage)
    {
        $billingPackage->update([
            'is_active' => !$billingPackage->is_active
        ]);

        $status = $billingPackage->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "Paket berhasil $status!");
    }
}

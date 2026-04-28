<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\InventoryItem;
use App\Models\InventoryTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InventoryInputController extends Controller
{
    public function create()
    {
        $products = Product::orderBy('name')->get();
        return view('user.inventory.masuk', compact('products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string',
            'proof_image' => 'nullable|image|max:2048',
        ]);

        $user = auth()->user();

        try {
            DB::beginTransaction();

            // 1. Create Transaction
            $transaction = InventoryTransaction::create([
                'minimarket_id' => $user->minimarket_id,
                'product_id' => $validated['product_id'],
                'user_id' => $user->id,
                'transaction_type' => 'in',
                'quantity' => $validated['quantity'],
                'status' => 'completed',
                'notes' => $validated['notes'],
            ]);

            if ($request->hasFile('proof_image')) {
                $path = $request->file('proof_image')->store('transactions', 'public');
                $transaction->update(['proof_image_path' => $path]);
            }

            // 2. Update Inventory
            $inventory = InventoryItem::firstOrCreate(
                ['minimarket_id' => $user->minimarket_id, 'product_id' => $validated['product_id']],
                ['quantity' => 0, 'last_updated' => now()]
            );

            $inventory->increment('quantity', $validated['quantity']);
            $inventory->update(['last_updated' => now()]);

            DB::commit();

            return redirect()->route('user.dashboard')
                ->with('success', 'Barang masuk berhasil dicatat.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal mencatat transaksi: ' . $e->getMessage());
        }
    }

    public function createKeluar()
    {
        $products = Product::orderBy('name')->get();
        return view('user.inventory.keluar', compact('products'));
    }

    public function storeKeluar(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string',
            'proof_image' => 'nullable|image|max:2048',
        ]);

        $user = auth()->user();

        // Check if stock exists
        $inventory = InventoryItem::where('minimarket_id', $user->minimarket_id)
            ->where('product_id', $validated['product_id'])
            ->first();

        if (!$inventory || $inventory->quantity < $validated['quantity']) {
            return back()->with('error', 'Stok tidak mencukupi.');
        }

        try {
            DB::beginTransaction();

            // 1. Create Transaction
            $transaction = InventoryTransaction::create([
                'minimarket_id' => $user->minimarket_id,
                'product_id' => $validated['product_id'],
                'user_id' => $user->id,
                'transaction_type' => 'out',
                'quantity' => $validated['quantity'],
                'status' => 'completed',
                'notes' => $validated['notes'],
            ]);

            if ($request->hasFile('proof_image')) {
                $path = $request->file('proof_image')->store('transactions', 'public');
                $transaction->update(['proof_image_path' => $path]);
            }

            // 2. Update Inventory
            $inventory->decrement('quantity', $validated['quantity']);
            $inventory->update(['last_updated' => now()]);

            DB::commit();

            return redirect()->route('user.dashboard')
                ->with('success', 'Barang keluar berhasil dicatat.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal mencatat transaksi: ' . $e->getMessage());
        }
    }
}

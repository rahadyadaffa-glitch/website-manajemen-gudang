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
        $categories = \App\Models\Category::whereNull('parent_id')->with('children')->orderBy('name')->get();
        return view('user.inventory.masuk', compact('categories'));
    }

    public function getProducts(Request $request)
    {
        $query = Product::query();
        $user = auth()->user();

        // If type is 'out', only show products that exist in the minimarket's inventory
        if ($request->type === 'out') {
            $query->whereHas('inventoryItems', function($q) use ($user) {
                $q->where('minimarket_id', $user->minimarket_id)
                  ->where('quantity', '>', 0);
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        $products = $query->orderBy('name')->get();

        return response()->json($products);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string',
            'custom_notes' => 'nullable|string|required_if:notes,Lainnya',
            'proof_image' => 'nullable|image|max:2048',
        ]);

        $finalNotes = $validated['notes'] === 'Lainnya' ? $validated['custom_notes'] : $validated['notes'];

        $user = auth()->user();

        try {
            DB::beginTransaction();

            // 1. Create Transaction (Status set to pending, to be approved by admin)
            $transaction = InventoryTransaction::create([
                'minimarket_id' => $user->minimarket_id,
                'product_id' => $validated['product_id'],
                'user_id' => $user->id,
                'transaction_type' => 'in',
                'quantity' => $validated['quantity'],
                'status' => 'pending',
                'notes' => $finalNotes,
            ]);

            if ($request->hasFile('proof_image')) {
                $path = $request->file('proof_image')->store('transactions', 'public');
                $transaction->update(['proof_image_path' => $path]);
            }

            // INVENTORY UPDATE REMOVED: Now handled by Admin Approval

            DB::commit();

            return redirect()->route('user.dashboard')
                ->with('success', 'Barang masuk telah diajukan dan menunggu persetujuan admin.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal mengajukan transaksi: ' . $e->getMessage());
        }
    }

    public function createKeluar()
    {
        $categories = \App\Models\Category::whereNull('parent_id')->with('children')->orderBy('name')->get();
        return view('user.inventory.keluar', compact('categories'));
    }

    public function storeKeluar(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string',
            'custom_notes' => 'nullable|string|required_if:notes,Lainnya',
            'proof_image' => 'nullable|image|max:2048',
        ]);

        $finalNotes = $validated['notes'] === 'Lainnya' ? $validated['custom_notes'] : $validated['notes'];

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

            // 1. Create Transaction (Status set to pending, to be approved by admin)
            $transaction = InventoryTransaction::create([
                'minimarket_id' => $user->minimarket_id,
                'product_id' => $validated['product_id'],
                'user_id' => $user->id,
                'transaction_type' => 'out',
                'quantity' => $validated['quantity'],
                'status' => 'pending',
                'notes' => $finalNotes,
            ]);

            if ($request->hasFile('proof_image')) {
                $path = $request->file('proof_image')->store('transactions', 'public');
                $transaction->update(['proof_image_path' => $path]);
            }

            // INVENTORY UPDATE REMOVED: Now handled by Admin Approval

            DB::commit();

            return redirect()->route('user.dashboard')
                ->with('success', 'Barang keluar telah diajukan dan menunggu persetujuan admin.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal mengajukan transaksi: ' . $e->getMessage());
        }
    }
    public function history()
    {
        $transactions = auth()->user()->inventoryTransactions()
            ->with(['product', 'minimarket'])
            ->latest()
            ->paginate(15);

        return view('user.history.index', compact('transactions'));
    }
}

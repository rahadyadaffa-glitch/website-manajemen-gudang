<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\InventoryItem;
use App\Models\InventoryTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InventoryInputController extends Controller
{
    public function create($type)
    {
        // Map URL slugs to internal types
        $mappedType = ($type === 'inputmasuk') ? 'in' : 'out';
        
        $categories = \App\Models\Category::whereNull('parent_id')->with('children')->orderBy('name')->get();
        return view('user.inventory.index', [
            'categories' => $categories,
            'type' => $mappedType,
            'slug' => $type
        ]);
    }

    public function getProducts(Request $request)
    {
        $categoryId = $request->query('category_id');
        $search = $request->query('search');
        
        $query = Product::query()->select('id', 'name');

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        if ($search) {
            $query->where('name', 'like', "%{$search}%");
        }

        $products = $query->orderBy('name')->get();

        return response()->json($products);
    }

    public function getVariants(Request $request, $productId)
    {
        $type = $request->query('type', 'in');
        $product = Product::findOrFail($productId);
        
        $query = $product->variants();

        if ($type === 'out') {
            $minimarketId = auth()->user()->minimarket_id;
            $query->whereHas('inventoryItems', function($q) use ($minimarketId) {
                $q->where('minimarket_id', $minimarketId)
                  ->where('quantity', '>', 0);
            });
        }

        $variants = $query->get()->map(function($v) {
            return [
                'id' => $v->id,
                'weight_label' => $v->weight_value . ' ' . $v->weight_unit,
                'unit' => $v->unit,
                'pcs_per_dus' => $v->pcs_per_dus,
                'sku' => $v->sku
            ];
        });

        return response()->json($variants);
    }

    // Unified store method
    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:in,out',
            'product_variant_id' => 'required|exists:product_variants,id',
            'quantity_input' => 'required|numeric|min:0.01',
            'input_unit' => 'required|in:pcs,dus',
            'notes' => 'nullable|string',
            'custom_notes' => 'nullable|string|required_if:notes,Lainnya',
            'proof_image' => 'nullable|image|max:2048',
        ]);

        $variant = ProductVariant::findOrFail($validated['product_variant_id']);
        
        // Conversion logic: Always store in smallest unit (Pcs)
        $finalQuantity = $validated['input_unit'] === 'dus' 
            ? round($validated['quantity_input'] * $variant->pcs_per_dus)
            : round($validated['quantity_input']);

        $finalNotes = $validated['notes'] === 'Lainnya' ? $validated['custom_notes'] : $validated['notes'];
        $user = auth()->user();

        if ($validated['type'] === 'out') {
            $inventory = InventoryItem::where('minimarket_id', $user->minimarket_id)
                ->where('product_variant_id', $variant->id)
                ->first();

            if (!$inventory || $inventory->quantity < $finalQuantity) {
                return back()->with('error', 'Stok tidak mencukupi.');
            }
        }

        try {
            DB::beginTransaction();

            $transaction = InventoryTransaction::create([
                'minimarket_id' => $user->minimarket_id,
                'product_variant_id' => $variant->id,
                'user_id' => $user->id,
                'transaction_type' => $validated['type'],
                'quantity' => $finalQuantity,
                'status' => 'pending',
                'notes' => $finalNotes,
            ]);

            if ($request->hasFile('proof_image')) {
                $path = $request->file('proof_image')->store('transactions', 'public');
                $transaction->update(['proof_image_path' => $path]);
            }

            DB::commit();

            $msg = $validated['type'] === 'in' ? 'Barang masuk' : 'Barang keluar';
            return redirect()->route('user.dashboard')
                ->with('success', "$msg telah diajukan dan menunggu persetujuan admin.");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal mengajukan transaksi: ' . $e->getMessage());
        }
    }

    public function history(Request $request)
    {
        $query = auth()->user()->inventoryTransactions()
            ->with(['productVariant.product.category', 'minimarket']);

        if ($request->filled('type')) {
            $query->where('transaction_type', $request->type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('category_id')) {
            $query->whereHas('productVariant.product', function($q) use ($request) {
                $q->where('category_id', $request->category_id);
            });
        } elseif ($request->filled('parent_category_id')) {
            $query->whereHas('productVariant.product.category', function($q) use ($request) {
                $q->where('parent_id', $request->parent_category_id);
            });
        }

        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('productVariant.product', function($pq) use ($search) {
                    $pq->where('name', 'like', "%{$search}%");
                })->orWhereHas('productVariant', function($vq) use ($search) {
                    $vq->where('sku', 'like', "%{$search}%");
                });
            });
        }

        $transactions = $query->latest()->paginate(15);
        $categories = \App\Models\Category::whereNull('parent_id')->with('children')->orderBy('name')->get();

        if ($request->ajax()) {
            return view('user.history._table_body', compact('transactions'))->render();
        }

        return view('user.history.index', compact('transactions', 'categories'));
    }
}

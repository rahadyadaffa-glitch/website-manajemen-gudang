<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\InventoryItem;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $minimarket = auth()->user()->minimarket;
        $parentCategoryId = $request->input('parent_category_id');
        $categoryId = $request->input('category_id');
        $date = $request->input('date');
        $search = $request->input('search');
        $status = $request->input('status');

        $query = InventoryItem::where('inventory_items.minimarket_id', $minimarket->id)
            ->join('product_variants', 'inventory_items.product_variant_id', '=', 'product_variants.id')
            ->join('products', 'product_variants.product_id', '=', 'products.id')
            ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
            ->select('inventory_items.*');

        if ($request->filled('search')) {
            $query->where(function($q) use ($search) {
                $q->where('products.name', 'like', "%{$search}%")
                  ->orWhere('product_variants.sku', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category_id')) {
            $query->where('products.category_id', $categoryId);
        } elseif ($request->filled('parent_category_id')) {
            $query->where('categories.parent_id', $parentCategoryId);
        }

        if ($date) {
            $query->whereDate('inventory_items.last_updated', $date);
        }

        if ($status === 'rejected') {
            $query->whereColumn('inventory_items.quantity', '<=', 'product_variants.min_stock_threshold');
        } elseif ($status === 'approved') {
            $query->whereColumn('inventory_items.quantity', '>', 'product_variants.min_stock_threshold');
        }

        $inventory = $query->latest('inventory_items.last_updated')
            ->with(['productVariant.product.category'])
            ->get();

        if ($request->ajax()) {
            return view('admin.products._table_body', compact('inventory'))->render();
        }

        $categories = Category::whereNull('parent_id')->with('children')->orderBy('name')->get();
        return view('admin.products.index', compact('inventory', 'categories'));
    }

    public function show(Request $request, \App\Models\ProductVariant $product)
    {
        $minimarket = auth()->user()->minimarket;
        $date = $request->input('date');

        $inventory = InventoryItem::where('minimarket_id', $minimarket->id)
            ->where('product_variant_id', $product->id)
            ->first();

        $query = \App\Models\InventoryTransaction::where('minimarket_id', $minimarket->id)
            ->where('product_variant_id', $product->id)
            ->with('user')
            ->latest();

        if ($date) {
            $query->whereDate('created_at', $date);
            $transactions = $query->get();
        } else {
            $transactions = $query->take(10)->get();
        }

        return view('admin.products.show', [
            'productVariant' => $product,
            'inventory' => $inventory,
            'transactions' => $transactions,
            'date' => $date
        ]);
    }

    public function create() { abort(403); }
    public function store(Request $request) { abort(403); }
    public function edit(Product $product) { abort(403); }
    public function update(Request $request, Product $product) { abort(403); }
    public function destroy(Product $product) { abort(403); }
}

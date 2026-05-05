<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InventoryTransaction;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        $minimarket = auth()->user()->minimarket;
        
        $query = InventoryTransaction::where('inventory_transactions.minimarket_id', $minimarket->id)
            ->join('product_variants', 'inventory_transactions.product_variant_id', '=', 'product_variants.id')
            ->join('products', 'product_variants.product_id', '=', 'products.id')
            ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
            ->select('inventory_transactions.*')
            ->with(['productVariant.product', 'user']);

        if ($request->filled('date')) {
            $query->whereDate('inventory_transactions.created_at', $request->date);
        }

        if ($request->filled('time_start')) {
            $query->whereTime('inventory_transactions.created_at', '>=', $request->time_start);
        }

        if ($request->filled('time_end')) {
            $query->whereTime('inventory_transactions.created_at', '<=', $request->time_end);
        }

        if ($request->filled('category_id')) {
            $query->where('products.category_id', $request->category_id);
        } elseif ($request->filled('parent_category_id')) {
            $query->where('categories.parent_id', $request->parent_category_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('products.name', 'like', "%{$search}%")
                  ->orWhere('product_variants.sku', 'like', "%{$search}%");
            });
        }

        $logs = $query->latest('inventory_transactions.created_at')->paginate(20)->withQueryString();

        if ($request->ajax()) {
            return view('admin.audit._table_body', compact('logs'))->render();
        }

        $categories = \App\Models\Category::whereNull('parent_id')->with('children')->orderBy('name')->get();

        return view('admin.audit.index', compact('logs', 'minimarket', 'categories'));
    }
}

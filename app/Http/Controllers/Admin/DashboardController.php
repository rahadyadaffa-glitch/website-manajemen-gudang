<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\InventoryItem;
use App\Models\InventoryTransaction;
use App\Models\Product;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $minimarket = auth()->user()->minimarket;
        $date = $request->input('date');
        $parentCategoryId = $request->input('parent_category_id');
        $categoryId = $request->input('category_id');
        $chartRange = $request->input('chart_range', '7');

        // Base queries with joins for category filtering
        $queryIn = InventoryTransaction::where('inventory_transactions.minimarket_id', $minimarket->id)
            ->where('inventory_transactions.transaction_type', 'in')
            ->where('inventory_transactions.status', 'approved')
            ->join('products', 'inventory_transactions.product_id', '=', 'products.id')
            ->leftJoin('categories', 'products.category_id', '=', 'categories.id');

        $queryOut = InventoryTransaction::where('inventory_transactions.minimarket_id', $minimarket->id)
            ->where('inventory_transactions.transaction_type', 'out')
            ->where('inventory_transactions.status', 'approved')
            ->join('products', 'inventory_transactions.product_id', '=', 'products.id')
            ->leftJoin('categories', 'products.category_id', '=', 'categories.id');

        if ($date) {
            $queryIn->whereDate('inventory_transactions.created_at', $date);
            $queryOut->whereDate('inventory_transactions.created_at', $date);
        }

        if ($request->filled('category_id')) {
            $queryIn->where('products.category_id', $categoryId);
            $queryOut->where('products.category_id', $categoryId);
        } elseif ($request->filled('parent_category_id')) {
            $queryIn->where('categories.parent_id', $parentCategoryId);
            $queryOut->where('categories.parent_id', $parentCategoryId);
        }

        $stats = [
            'total_items' => InventoryItem::where('minimarket_id', $minimarket->id)->sum('quantity'),
            'low_stock_count' => InventoryItem::where('minimarket_id', $minimarket->id)
                ->where('quantity', '<', 10)
                ->count(),
            'pending_approval' => InventoryTransaction::where('minimarket_id', $minimarket->id)
                ->where('status', 'pending')
                ->count(),
            'total_in_period' => $queryIn->sum('inventory_transactions.quantity'),
            'total_out_period' => $queryOut->sum('inventory_transactions.quantity'),
        ];

        $recent_transactions = InventoryTransaction::where('minimarket_id', $minimarket->id)
            ->with(['product', 'user'])
            ->latest()
            ->take(5)
            ->get();

        // Chart Data
        $chart_data = ['labels' => [], 'in' => [], 'out' => []];
        $days = intval($chartRange);
        
        for ($i = $days - 1; $i >= 0; $i--) {
            $currentDate = Carbon::today()->subDays($i);
            $label = $currentDate->format('d M');
            
            $dayInQuery = InventoryTransaction::where('inventory_transactions.minimarket_id', $minimarket->id)
                ->where('inventory_transactions.transaction_type', 'in')
                ->where('inventory_transactions.status', 'approved')
                ->whereDate('inventory_transactions.created_at', $currentDate)
                ->join('products', 'inventory_transactions.product_id', '=', 'products.id')
                ->leftJoin('categories', 'products.category_id', '=', 'categories.id');

            $dayOutQuery = InventoryTransaction::where('inventory_transactions.minimarket_id', $minimarket->id)
                ->where('inventory_transactions.transaction_type', 'out')
                ->where('inventory_transactions.status', 'approved')
                ->whereDate('inventory_transactions.created_at', $currentDate)
                ->join('products', 'inventory_transactions.product_id', '=', 'products.id')
                ->leftJoin('categories', 'products.category_id', '=', 'categories.id');

            if ($request->filled('category_id')) {
                $dayInQuery->where('products.category_id', $categoryId);
                $dayOutQuery->where('products.category_id', $categoryId);
            } elseif ($request->filled('parent_category_id')) {
                $dayInQuery->where('categories.parent_id', $parentCategoryId);
                $dayOutQuery->where('categories.parent_id', $parentCategoryId);
            }
                
            $in = $dayInQuery->sum('inventory_transactions.quantity');
            $out = $dayOutQuery->sum('inventory_transactions.quantity');

            $chart_data['labels'][] = $label;
            $chart_data['in'][] = $in;
            $chart_data['out'][] = $out;
        }

        $categories = Category::whereNull('parent_id')->with('children')->orderBy('name')->get();

        return view('admin.dashboard', compact('minimarket', 'stats', 'recent_transactions', 'chart_data', 'categories'));
    }
}

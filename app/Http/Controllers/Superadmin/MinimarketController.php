<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\Minimarket;
use Illuminate\Http\Request;

class MinimarketController extends Controller
{
    public function index()
    {
        $minimarkets = Minimarket::latest()->get();
        return view('superadmin.minimarkets.index', compact('minimarkets'));
    }

    public function create()
    {
        return view('superadmin.minimarkets.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:200',
            'code' => 'required|string|max:20|unique:minimarkets',
            'address' => 'required|string',
            'city' => 'required|string|max:100',
            'province' => 'required|string|max:100',
            'phone' => 'nullable|string|max:20',
        ]);

        Minimarket::create($validated);

        return redirect()->route('superadmin.minimarkets.index')
            ->with('success', 'Minimarket berhasil ditambahkan.');
    }

    public function edit(Minimarket $minimarket)
    {
        return view('superadmin.minimarkets.edit', compact('minimarket'));
    }

    public function update(Request $request, Minimarket $minimarket)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:200',
            'code' => 'required|string|max:20|unique:minimarkets,code,' . $minimarket->id,
            'address' => 'required|string',
            'city' => 'required|string|max:100',
            'province' => 'required|string|max:100',
            'phone' => 'nullable|string|max:20',
            'status' => 'required|in:active,archived',
        ]);

        $minimarket->update($validated);

        return redirect()->route('superadmin.minimarkets.index')
            ->with('success', 'Minimarket berhasil diperbarui.');
    }

    public function show(Request $request, Minimarket $minimarket)
    {
        $date = $request->date;
        $query = $minimarket->inventoryTransactions();
        
        if ($date) {
            $query->whereDate('created_at', $date);
        }

        // Stats specific to this minimarket and date
        $stats = [
            'total_products' => $minimarket->inventoryItems()->count(),
            'total_stock' => $minimarket->inventoryItems()->sum('quantity'),
            'recent_in' => (clone $query)->where('transaction_type', 'in')->sum('quantity'),
            'recent_out' => (clone $query)->where('transaction_type', 'out')->sum('quantity'),
        ];

        $inventoryQuery = $minimarket->inventoryItems()->with('product.category');

        if ($request->filled('search')) {
            $inventoryQuery->whereHas('product', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('sku', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('category_id')) {
            $inventoryQuery->whereHas('product', function ($q) use ($request) {
                $q->where('category_id', $request->category_id);
            });
        }

        $inventory = $inventoryQuery->latest()->take(20)->get();

        // Calculate historical stock if date is provided
        if ($request->filled('date')) {
            $date = $request->date;
            foreach ($inventory as $item) {
                $futureTransactions = $minimarket->inventoryTransactions()
                    ->where('product_id', $item->product_id)
                    ->where('created_at', '>', \Carbon\Carbon::parse($date)->endOfDay());
                
                $inSinceDate = (clone $futureTransactions)->where('transaction_type', 'in')->sum('quantity');
                $outSinceDate = (clone $futureTransactions)->where('transaction_type', 'out')->sum('quantity');
                
                // Adjust quantity: subtract future 'in', add future 'out'
                $item->quantity = $item->quantity - $inSinceDate + $outSinceDate;
            }

            // Also update the global stats['total_stock'] for historical accuracy
            $futureAll = $minimarket->inventoryTransactions()
                ->where('created_at', '>', \Carbon\Carbon::parse($date)->endOfDay());
            
            $allInSince = (clone $futureAll)->where('transaction_type', 'in')->sum('quantity');
            $allOutSince = (clone $futureAll)->where('transaction_type', 'out')->sum('quantity');
            
            $stats['total_stock'] = $stats['total_stock'] - $allInSince + $allOutSince;
        }

        if ($request->ajax()) {
            return view('superadmin.minimarkets.partials._inventory_table', compact('inventory'))->render();
        }

        $transactions = $query->with(['product', 'user'])->latest()->take(10)->get();
        $categories = \App\Models\Category::all();

        return view('superadmin.minimarkets.show', compact('minimarket', 'stats', 'inventory', 'transactions', 'categories'));
    }

    public function trend(Request $request, Minimarket $minimarket)
    {
        $date = $request->date;
        $query = $minimarket->inventoryTransactions();
        
        if ($date) {
            $query->whereDate('created_at', $date);
        }

        // Stats specific to this minimarket
        $stats = [
            'total_products' => $minimarket->inventoryItems()->count(),
            'total_stock' => $minimarket->inventoryItems()->sum('quantity'),
            'recent_in' => (clone $query)->where('transaction_type', 'in')->sum('quantity'),
            'recent_out' => (clone $query)->where('transaction_type', 'out')->sum('quantity'),
        ];

        $transactions = $query->with(['product', 'user'])->latest()->take(10)->get();

        // Calculate 30-day trend data for THIS minimarket
        $chart_data = ['labels' => [], 'in' => [], 'out' => []];
        for ($i = 29; $i >= 0; $i--) {
            $date_trend = now()->subDays($i)->format('Y-m-d');
            $label = now()->subDays($i)->format('d M');
            
            $in = $minimarket->inventoryTransactions()->where('transaction_type', 'in')
                ->whereDate('created_at', $date_trend)
                ->sum('quantity');
                
            $out = $minimarket->inventoryTransactions()->where('transaction_type', 'out')
                ->whereDate('created_at', $date_trend)
                ->sum('quantity');

            $chart_data['labels'][] = $label;
            $chart_data['in'][] = $in;
            $chart_data['out'][] = $out;
        }

        return view('superadmin.minimarkets.trend', compact('minimarket', 'stats', 'transactions', 'chart_data'));
    }

    public function destroy(Minimarket $minimarket)
    {
        $minimarket->delete(); // Soft delete

        return redirect()->route('superadmin.minimarkets.index')
            ->with('success', 'Minimarket berhasil diarsipkan.');
    }
}

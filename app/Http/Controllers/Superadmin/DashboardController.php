<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Minimarket;
use App\Models\User;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->input('date');

        $queryIn = \App\Models\InventoryTransaction::where('transaction_type', 'in');
        $queryOut = \App\Models\InventoryTransaction::where('transaction_type', 'out');

        if ($date) {
            $queryIn->whereDate('created_at', $date);
            $queryOut->whereDate('created_at', $date);
        }

        $stats = [
            'total_minimarkets' => Minimarket::count(),
            'total_in' => $queryIn->sum('quantity'),
            'total_out' => $queryOut->sum('quantity'),
        ];

        $recent_activities = [
            ['user' => 'John Doe', 'action' => 'Input Barang Masuk', 'target' => 'Beras 5kg', 'time' => '5 menit yang lalu'],
            ['user' => 'Jane Smith', 'action' => 'Update Stok', 'target' => 'Minyak Goreng', 'time' => '12 menit yang lalu'],
            ['user' => 'Admin MM001', 'action' => 'Approve Adjustment', 'target' => 'Indomie Goreng', 'time' => '45 menit yang lalu'],
        ];

        // Calculate trend data based on range
        $range = $request->input('chart_range', '30');
        $chart_data = ['labels' => [], 'in' => [], 'out' => []];

        if ($range === 'all') {
            // Group by Month for All Time
            $firstTransaction = \App\Models\InventoryTransaction::orderBy('created_at', 'asc')->first();
            $startDate = $firstTransaction ? $firstTransaction->created_at->startOfMonth() : now()->subMonths(11)->startOfMonth();
            $endDate = now()->endOfMonth();

            $current = clone $startDate;
            while ($current <= $endDate) {
                $label = $current->format('M Y');
                
                $in = \App\Models\InventoryTransaction::where('transaction_type', 'in')
                    ->whereMonth('created_at', $current->month)
                    ->whereYear('created_at', $current->year)
                    ->sum('quantity');
                    
                $out = \App\Models\InventoryTransaction::where('transaction_type', 'out')
                    ->whereMonth('created_at', $current->month)
                    ->whereYear('created_at', $current->year)
                    ->sum('quantity');

                $chart_data['labels'][] = $label;
                $chart_data['in'][] = $in;
                $chart_data['out'][] = $out;
                $current->addMonth();
            }
        } else {
            // Group by Day for 7, 30, 90 days
            $days = intval($range);
            for ($i = $days - 1; $i >= 0; $i--) {
                $date = now()->subDays($i)->format('Y-m-d');
                $label = now()->subDays($i)->format('d M');
                
                $in = \App\Models\InventoryTransaction::where('transaction_type', 'in')
                    ->whereDate('created_at', $date)
                    ->sum('quantity');
                    
                $out = \App\Models\InventoryTransaction::where('transaction_type', 'out')
                    ->whereDate('created_at', $date)
                    ->sum('quantity');

                $chart_data['labels'][] = $label;
                $chart_data['in'][] = $in;
                $chart_data['out'][] = $out;
            }
        }

        $minimarkets = Minimarket::with('admin')->get();

        return view('superadmin.dashboard', compact('stats', 'recent_activities', 'minimarkets', 'chart_data'));
    }
}

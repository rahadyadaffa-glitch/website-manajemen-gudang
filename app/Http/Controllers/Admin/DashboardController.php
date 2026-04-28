<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $minimarket = auth()->user()->minimarket;

        // Dummy stats for now
        $stats = [
            'total_items' => 450,
            'low_stock_count' => 3,
            'pending_approval' => 2,
            'today_transactions' => 12,
        ];

        $recent_transactions = [
            ['type' => 'Masuk', 'product' => 'Beras 5kg', 'qty' => '+20', 'user' => 'Staff 01', 'status' => 'completed'],
            ['type' => 'Keluar', 'product' => 'Minyak Goreng 2L', 'qty' => '-5', 'user' => 'Staff 02', 'status' => 'completed'],
            ['type' => 'Adjustment', 'product' => 'Gula Pasir 1kg', 'qty' => '-2', 'user' => 'Staff 01', 'status' => 'pending'],
        ];

        return view('admin.dashboard', compact('minimarket', 'stats', 'recent_transactions'));
    }
}

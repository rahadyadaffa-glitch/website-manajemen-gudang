<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $minimarket = auth()->user()->minimarket;

        $stats = [
            'my_transactions_today' => auth()->user()->inventoryTransactions()
                ->whereDate('created_at', now()->toDateString())
                ->count(),
            'total_items' => auth()->user()->minimarket->inventoryItems()
                ->sum('quantity'),
        ];

        return view('user.dashboard', compact('minimarket', 'stats'));
    }
}

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
            'my_transactions_today' => 5,
            'total_items' => 450,
        ];

        return view('user.dashboard', compact('minimarket', 'stats'));
    }
}

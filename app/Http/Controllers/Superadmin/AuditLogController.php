<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\InventoryTransaction;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        $query = InventoryTransaction::with(['minimarket', 'product', 'user']);

        if ($request->filled('minimarket_id')) {
            $query->where('minimarket_id', $request->minimarket_id);
        }

        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        if ($request->filled('time_start')) {
            $query->whereTime('created_at', '>=', $request->time_start);
        }

        if ($request->filled('time_end')) {
            $query->whereTime('created_at', '<=', $request->time_end);
        }

        $logs = $query->latest()->paginate(20)->withQueryString();
        $minimarkets = \App\Models\Minimarket::all();

        return view('superadmin.audit.index', compact('logs', 'minimarkets'));
    }
}

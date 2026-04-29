<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InventoryTransaction;
use App\Models\InventoryItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApprovalController extends Controller
{
    public function index()
    {
        $minimarket = auth()->user()->minimarket;
        $pending_transactions = InventoryTransaction::where('minimarket_id', $minimarket->id)
            ->where('status', 'pending')
            ->with(['product', 'user'])
            ->latest()
            ->paginate(15);

        return view('admin.approvals.index', compact('pending_transactions'));
    }

    public function approve(Request $request, InventoryTransaction $transaction)
    {
        if ($transaction->status !== 'pending') {
            return back()->with('error', 'Transaksi ini sudah diproses.');
        }

        try {
            DB::beginTransaction();

            // 1. Update Transaction
            $transaction->update([
                'status' => 'approved',
                'approved_by' => auth()->id(),
                'approved_at' => now(),
            ]);

            // 2. Update Inventory
            $inventory = InventoryItem::firstOrCreate(
                ['minimarket_id' => $transaction->minimarket_id, 'product_id' => $transaction->product_id],
                ['quantity' => 0, 'last_updated' => now()]
            );

            if ($transaction->transaction_type === 'in') {
                $inventory->increment('quantity', $transaction->quantity);
            } elseif ($transaction->transaction_type === 'out') {
                // Check if stock is sufficient for 'out' transaction
                if ($inventory->quantity < $transaction->quantity) {
                    throw new \Exception('Stok tidak mencukupi untuk menyetujui pengeluaran ini.');
                }
                $inventory->decrement('quantity', $transaction->quantity);
            }

            $inventory->update(['last_updated' => now()]);

            DB::commit();

            return back()->with('success', 'Transaksi berhasil disetujui.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menyetujui transaksi: ' . $e->getMessage());
        }
    }

    public function reject(Request $request, InventoryTransaction $transaction)
    {
        if ($transaction->status !== 'pending') {
            return back()->with('error', 'Transaksi ini sudah diproses.');
        }

        $transaction->update([
            'status' => 'rejected',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
            'notes' => $request->notes ?? $transaction->notes,
        ]);

        return back()->with('success', 'Transaksi telah ditolak.');
    }
}

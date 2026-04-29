<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\InventoryItem;
use App\Models\InventoryTransaction;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $minimarket = auth()->user()->minimarket;
        $date = $request->input('date');
        $categoryId = $request->input('category_id');

        // Current totals
        $currentQuery = InventoryItem::where('minimarket_id', $minimarket->id);
        if ($categoryId) {
            $currentQuery->whereHas('product', function($q) use ($categoryId) {
                $q->where('category_id', $categoryId);
            });
        }
        $currentStock = $currentQuery->sum('quantity');

        // Filtered transactions for the period (the exact date)
        $trxQuery = InventoryTransaction::where('minimarket_id', $minimarket->id)
            ->where('status', 'approved');

        if ($date) {
            $trxQuery->whereDate('created_at', $date);
        }

        if ($categoryId) {
            $trxQuery->whereHas('product', function($q) use ($categoryId) {
                $q->where('category_id', $categoryId);
            });
        }

        $totalIn = (clone $trxQuery)->where('transaction_type', 'in')->sum('quantity');
        $totalOut = (clone $trxQuery)->where('transaction_type', 'out')->sum('quantity');

        // Historical Balance Calculation
        $displayStock = $currentStock;
        if ($date) {
            $futureQuery = InventoryTransaction::where('minimarket_id', $minimarket->id)
                ->where('status', 'approved')
                ->where('created_at', '>', Carbon::parse($date)->endOfDay());

            if ($categoryId) {
                $futureQuery->whereHas('product', function($q) use ($categoryId) {
                    $q->where('category_id', $categoryId);
                });
            }

            $futureIn = (clone $futureQuery)->where('transaction_type', 'in')->sum('quantity');
            $futureOut = (clone $futureQuery)->where('transaction_type', 'out')->sum('quantity');

            // Balance(date) = Current - FutureIn + FutureOut
            $displayStock = $currentStock - $futureIn + $futureOut;
        }

        // Product Summary for the report
        $productSummary = InventoryItem::where('minimarket_id', $minimarket->id)
            ->with(['product.category'])
            ->when($categoryId, function($q) use ($categoryId) {
                $q->whereHas('product', function($pq) use ($categoryId) {
                    $pq->where('category_id', $categoryId);
                });
            })
            ->get()
            ->map(function($item) use ($date) {
                // Calculate item-specific historical balance if needed
                $currentQty = $item->quantity;
                if ($date) {
                    $itemFuture = InventoryTransaction::where('minimarket_id', $item->minimarket_id)
                        ->where('product_id', $item->product_id)
                        ->where('status', 'approved')
                        ->where('created_at', '>', Carbon::parse($date)->endOfDay());
                    
                    $fIn = (clone $itemFuture)->where('transaction_type', 'in')->sum('quantity');
                    $fOut = (clone $itemFuture)->where('transaction_type', 'out')->sum('quantity');
                    
                    $item->display_qty = $currentQty - $fIn + $fOut;
                } else {
                    $item->display_qty = $currentQty;
                }
                
                // Get period-specific activity
                $periodTrx = InventoryTransaction::where('minimarket_id', $item->minimarket_id)
                    ->where('product_id', $item->product_id)
                    ->where('status', 'approved');
                
                if ($date) {
                    $periodTrx->whereDate('created_at', $date);
                }
                
                $item->period_in = (clone $periodTrx)->where('transaction_type', 'in')->sum('quantity');
                $item->period_out = (clone $periodTrx)->where('transaction_type', 'out')->sum('quantity');
                
                return $item;
            });

        $categories = Category::all();

        return view('admin.reports.index', compact('minimarket', 'displayStock', 'totalIn', 'totalOut', 'productSummary', 'categories'));
    }
}

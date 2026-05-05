<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\Minimarket;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->date;
        $category_id = $request->category_id;

        $minimarkets = Minimarket::withCount(['inventoryItems' => function ($query) use ($category_id) {
            if ($category_id) {
                $query->whereHas('productVariant.product', function ($q) use ($category_id) {
                    $q->where('category_id', $category_id);
                });
            }
        }])
        ->withSum(['inventoryItems as total_quantity' => function ($query) use ($category_id) {
            if ($category_id) {
                $query->whereHas('productVariant.product', function ($q) use ($category_id) {
                    $q->where('category_id', $category_id);
                });
            }
        }], 'quantity')
        ->withCount(['inventoryTransactions' => function ($query) use ($date, $category_id) {
            if ($date) {
                $query->whereDate('created_at', $date);
            }
            if ($category_id) {
                $query->whereHas('productVariant.product', function ($q) use ($category_id) {
                    $q->where('category_id', $category_id);
                });
            }
        }])->get();

        // Calculate filtered transaction sums and historical balance for each minimarket
        foreach ($minimarkets as $mm) {
            $trxQuery = $mm->inventoryTransactions();
            
            // Calculate historical balance if date is provided
            // Formula: Balance(at date) = CurrentBalance - Sum(Transactions from Date+1 to Now)
            $currentStock = $mm->total_quantity ?? 0;
            
            if ($date) {
                $futureTransactions = $mm->inventoryTransactions()
                    ->where('created_at', '>', \Carbon\Carbon::parse($date)->endOfDay());
                
                if ($category_id) {
                    $futureTransactions->whereHas('productVariant.product', function ($q) use ($category_id) {
                        $q->where('category_id', $category_id);
                    });
                }

                $inSinceDate = (clone $futureTransactions)->where('transaction_type', 'in')->sum('quantity');
                $outSinceDate = (clone $futureTransactions)->where('transaction_type', 'out')->sum('quantity');
                
                // Reverse the transactions: subtract IN, add OUT
                $mm->total_quantity = $currentStock - $inSinceDate + $outSinceDate;

                // For the "Periode" stats, we filter by EXACT date
                $trxQuery->whereDate('created_at', $date);
            }
            
            if ($category_id) {
                $trxQuery->whereHas('productVariant.product', function ($q) use ($category_id) {
                    $q->where('category_id', $category_id);
                });
            }

            $mm->recent_in = (clone $trxQuery)->where('transaction_type', 'in')->sum('quantity');
            $mm->recent_out = (clone $trxQuery)->where('transaction_type', 'out')->sum('quantity');
        }

        $categories = \App\Models\Category::all();

        return view('superadmin.reports.index', compact('minimarkets', 'categories'));
    }
}

<?php

namespace App\Http\Controllers\Cashier;

use App\Http\Controllers\Controller;
use App\Models\Order;

class TransactionController extends Controller
{
    public function statistics()
    {
        $totalOrders = Order::count();
        $completedOrders = Order::where('status', 'completed')->count();
        $totalRevenue = Order::where('status', 'completed')->sum('total_price');

        return view('cashier.statistics', compact('totalOrders', 'completedOrders', 'totalRevenue'));
    }
}

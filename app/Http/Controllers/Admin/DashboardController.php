<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users' => User::count(),
            'total_products' => Product::count(),
            'available_products' => Product::where('stock', '>', 0)->count(),
            'total_orders' => Order::count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'completed_orders' => Order::where('status', 'completed')->count(),
            'today_revenue' => Order::whereDate('created_at', today())
                                   ->where('status', 'completed')
                                   ->sum('total_price'),
            'low_stock_products' => Product::where('stock', '<=', 5)
                                          ->where('stock', '>', 0)
                                          ->take(5)
                                          ->get()
        ];

        return view('admin.dashboard', compact('stats'));
    }
}

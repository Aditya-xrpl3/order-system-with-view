<?php

namespace App\Http\Controllers\Cashier;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Carbon\Carbon;

class OrderController extends Controller
{
    public function index()
    {
        // Filter tanggal hari ini
        $today = Carbon::today();

        // Hitung total order hari ini
        $totalOrder = Order::whereDate('created_at', $today)->count();

        // Hitung order selesai hari ini
        $completedOrders = Order::where('status', 'completed')
                              ->whereDate('created_at', $today)
                              ->count();

        // Hitung order pending hari ini
        $pendingOrders = Order::where('status', 'pending')
                            ->whereDate('created_at', $today)
                            ->count();

        // Hitung total penjualan hari ini
        $totalSales = Order::whereDate('created_at', $today)
                         ->sum('total_price');

        // Ambil semua order untuk ditampilkan di tabel
        $orders = Order::with(['user', 'table'])
                     ->latest()
                     ->get();

        return view('cashier.orders.index', compact(
            'totalOrder',
            'completedOrders',
            'pendingOrders',
            'totalSales',
            'orders',
            'today'
        ));
    }

    public function show(Order $order)
    {
        $order->load(['orderItems.product', 'user', 'table']);
        return view('cashier.orders.show', compact('order'));
    }

    public function complete(Order $order)
    {
        $order->update(['status' => 'completed']);

        return redirect()->route('cashier.orders.index')
                        ->with('success', 'Pesanan berhasil diselesaikan!');
    }

    public function statistics()
    {
        $stats = [
            'todayRevenue' => Order::whereDate('created_at', today())
                                  ->where('status', 'completed')
                                  ->sum('total_price'),
            'totalOrders' => Order::count(),
            'averageOrder' => Order::where('status', 'completed')->avg('total_price'),
            'topProducts' => \DB::table('order_items')
                                ->join('products', 'order_items.product_id', '=', 'products.id')
                                ->join('orders', 'order_items.order_id', '=', 'orders.id')
                                ->where('orders.status', 'completed')
                                ->select('products.name', \DB::raw('SUM(order_items.quantity) as total_sold'))
                                ->groupBy('products.id', 'products.name')
                                ->orderBy('total_sold', 'desc')
                                ->take(5)
                                ->get()
        ];

        return view('cashier.statistics', compact('stats'));
    }
}

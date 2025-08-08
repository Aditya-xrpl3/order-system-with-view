<?php

namespace App\Http\Controllers\Cashier;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['user', 'table'])
                      ->latest()
                      ->get();

        $stats = [
            'totalOrders' => Order::count(),
            'completedOrders' => Order::where('status', 'completed')->count(),
            'pendingOrders' => Order::where('status', 'pending')->count(),
            'totalRevenue' => Order::where('status', 'completed')->sum('total_price')
        ];

        // Data chart: jumlah order completed per hari (7 hari terakhir)
        $completedPerDay = Order::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as total')
            )
            ->where('status', 'completed')
            ->where('created_at', '>=', now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Data chart: jumlah order pending per hari (7 hari terakhir)
        $pendingPerDay = Order::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as total')
            )
            ->where('status', 'pending')
            ->where('created_at', '>=', now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Gabungkan tanggal (agar sumbu X konsisten)
        $dates = collect();
        foreach ($completedPerDay as $row) $dates->push($row->date);
        foreach ($pendingPerDay as $row) $dates->push($row->date);
        $dates = $dates->unique()->sort()->values();

        // Siapkan data untuk chart.js
        $completedData = [];
        $pendingData = [];
        foreach ($dates as $date) {
            $completedData[] = optional($completedPerDay->firstWhere('date', $date))->total ?? 0;
            $pendingData[] = optional($pendingPerDay->firstWhere('date', $date))->total ?? 0;
        }

        return view('cashier.orders.index', compact(
            'orders', 'stats',
            'dates', 'completedData', 'pendingData'
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

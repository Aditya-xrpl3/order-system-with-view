<?php

namespace App\Http\Controllers\Cashier;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Exports\OrderExport;
use Maatwebsite\Excel\Facades\Excel;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->get('filter', 'all');

        $query = Order::with(['user', 'table'])
            ->orderBy('created_at', 'desc');

        // Apply filters
        switch ($filter) {
            case 'pending':
                $query->where('status', 'pending');
                break;
            case 'completed':
                $query->where('status', 'completed');
                break;
            case 'today':
                $query->whereDate('created_at', now()->toDateString());
                break;
        }

        $orders = $query->get();

        // Get statistics
        $totalOrders = Order::count();
        $completedOrders = Order::where('status', 'completed')->count();
        $pendingOrders = Order::where('status', 'pending')->count();
        $totalRevenue = Order::where('status', 'completed')->sum('total_price');

        return view('cashier.orders.index', compact(
            'orders',
            'totalOrders',
            'completedOrders',
            'pendingOrders',
            'totalRevenue',
            'filter'
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

    /**
     * Export statistik penjualan sebagai Excel
     */
    public function exportStatistics(Request $request)
    {
        $timeframe = $request->get('timeframe', 'today');
        $fileName = 'statistik-penjualan-' . $timeframe . '.xlsx';

        // Ambil tanggal berdasarkan timeframe
        $startDate = now();
        $endDate = now();

        switch ($timeframe) {
            case 'week':
                $startDate = $startDate->startOfWeek();
                break;
            case 'month':
                $startDate = $startDate->startOfMonth();
                break;
            case 'year':
                $startDate = $startDate->startOfYear();
                break;
            default:
                $startDate = $startDate->startOfDay();
        }

        // Gunakan OrderExport untuk mengekspor data ke Excel
        return Excel::download(new OrderExport($startDate, $endDate), $fileName);
    }
}

<?php

namespace App\Http\Controllers\Cashier;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['items', 'table', 'user'])->get();

        // Statistik
        $totalOrders = Order::count();
        $completedOrders = Order::where('status', 'completed')->count();
        $pendingOrders = Order::where('status', 'pending')->count();
        $totalRevenue = Order::where('status', 'completed')->sum('total_price');

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
            'orders', 'totalOrders', 'completedOrders', 'pendingOrders', 'totalRevenue',
            'dates', 'completedData', 'pendingData'
        ));
    }

    public function show(Order $order)
    {
        // $this->authorize('view', $order);

        return view('cashier.orders.show', compact('order'));
    }

    public function complete(Order $order)
    {
        $order->update(['status' => 'completed']);
        return redirect()->route('cashier.orders.index')->with('success', 'Order completed!');
    }
}

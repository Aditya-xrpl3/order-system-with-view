<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Table;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $orders = $user->role === 'admin' || $user->role === 'cashier'
            ? Order::with(['items.product', 'table', 'user'])
                ->latest()
                ->get()
            : $user->orders()
                ->with(['items.product', 'table'])
                ->latest()
                ->get();

        return response()->json($orders);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'table_id' => 'required|exists:tables,id',
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.notes' => 'nullable|string',
        ]);

        try {
            return DB::transaction(function () use ($request, $validated) {
                $order = Order::create([
                    'user_id' => $request->user()->id,
                    'table_id' => $validated['table_id'],
                    'status' => 'pending',
                    'total_price' => 0, // Will be calculated
                ]);

                $totalPrice = 0;

                foreach ($validated['items'] as $item) {
                    $product = Product::findOrFail($item['product_id']);

                    // Check stock
                    if ($product->stock < $item['quantity']) {
                        throw new \Exception("Not enough stock for {$product->name}");
                    }

                    // Decrease product stock
                    $product->decrement('stock', $item['quantity']);

                    $orderItem = OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                        'quantity' => $item['quantity'],
                        'notes' => $item['notes'] ?? null,
                        'price' => $product->price,
                    ]);

                    $totalPrice += $product->price * $item['quantity'];
                }

                // Update table availability
                $table = Table::findOrFail($validated['table_id']);
                $table->update(['is_available' => false]);

                // Update order total
                $order->update(['total_price' => $totalPrice]);

                return response()->json([
                    'message' => 'Order created successfully',
                    'order' => $order->load('items.product'),
                ], 201);
            });
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create order',
                'error' => $e->getMessage(),
            ], 422);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Order $order)
    {
        $order->load(['items.product', 'table', 'user']);
        return response()->json($order);
    }

    /**
     * Mark an order as completed.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\JsonResponse
     */
    public function complete(Order $order)
    {
        try {
            return DB::transaction(function () use ($order) {
                // Update order status
                $order->update(['status' => 'completed']);

                // Make table available again
                $table = $order->table;
                $table->update(['is_available' => true]);

                return response()->json([
                    'message' => 'Order completed successfully',
                    'order' => $order->fresh()->load('items.product'),
                ]);
            });
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to complete order',
                'error' => $e->getMessage(),
            ], 422);
        }
    }

    /**
     * Get pending orders.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function pendingOrders()
    {
        $pendingOrders = Order::where('status', 'pending')
            ->with(['items.product', 'table', 'user'])
            ->latest()
            ->get();

        return response()->json($pendingOrders);
    }

    /**
     * Get order statistics.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function statistics(Request $request)
    {
        $timeframe = $request->get('timeframe', 'today');

        // Define the date range based on the timeframe
        $startDate = now();
        $endDate = now();

        switch ($timeframe) {
            case 'today':
                $startDate = $startDate->startOfDay();
                break;
            case 'week':
                $startDate = $startDate->startOfWeek();
                break;
            case 'month':
                $startDate = $startDate->startOfMonth();
                break;
            case 'year':
                $startDate = $startDate->startOfYear();
                break;
        }

        // Get orders data
        $ordersCount = Order::whereBetween('created_at', [$startDate, $endDate])->count();
        $completedOrdersCount = Order::where('status', 'completed')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();
        $totalRevenue = Order::where('status', 'completed')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('total_price');

        // Get popular products
        $popularProducts = OrderItem::join('products', 'order_items.product_id', '=', 'products.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->select(
                'products.id',
                'products.name',
                DB::raw('SUM(order_items.quantity) as total_quantity'),
                DB::raw('SUM(order_items.price * order_items.quantity) as total_revenue')
            )
            ->groupBy('products.id', 'products.name')
            ->orderBy('total_quantity', 'desc')
            ->limit(5)
            ->get();

        return response()->json([
            'orders_count' => $ordersCount,
            'completed_orders_count' => $completedOrdersCount,
            'completion_rate' => $ordersCount > 0 ? ($completedOrdersCount / $ordersCount) * 100 : 0,
            'total_revenue' => $totalRevenue,
            'popular_products' => $popularProducts,
            'timeframe' => $timeframe,
        ]);
    }
}

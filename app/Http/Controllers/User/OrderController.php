<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Table;
use App\Models\CartItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::where('user_id', auth()->id())
                      ->with(['table', 'orderItems.product'])
                      ->latest()
                      ->get();

        $products = Product::where('stock', '>', 0)->get();

        $selectedTable = session('selected_table') ?
                        Table::find(session('selected_table')) : null;

        return view('user.orders.index', compact('orders', 'products', 'selectedTable'));
    }

    public function show(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        $order->load(['orderItems.product', 'table']);
        return view('user.orders.show', compact('order'));
    }

    public function create()
    {
        $tables = Table::where('is_available', true)->get();
        return view('user.orders.create', compact('tables'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'table_id' => 'required|exists:tables,id',
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1'
        ]);

        $order = DB::transaction(function () use ($request) {
            $total = 0;
            $itemsData = [];

            $cartItems = CartItem::where('user_id', auth()->id())->with('product')->get();

            foreach ($request->items as $item) {
                $product = Product::findOrFail($item['product_id']);
                $subtotal = $product->price * $item['quantity'];
                $total += $subtotal;

                $itemsData[] = [
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price' => $product->price,
                ];
            }

            $order = Order::create([
                'user_id' => auth()->id(),
                'table_id' => $request->table_id,
                'status' => 'pending',
                'total_price' => $total
            ]);

            foreach ($itemsData as $data) {
                $data['order_id'] = $order->id;
                OrderItem::create($data);
            }

            // Hapus item dari keranjang
            CartItem::where('user_id', auth()->id())->delete();

            return $order;
        });


        return redirect()->route('orders.show', $order);
    }

    public function downloadReceipt($orderId)
    {
        $order = Order::with(['items.product', 'table', 'user'])->findOrFail($orderId);
        $pdf = Pdf::loadView('receipts.pdf', compact('order'));
        return $pdf->download('receipt-order-'.$order->id.'.pdf');
    }
}

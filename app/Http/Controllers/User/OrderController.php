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
        try {
            $order = DB::transaction(function () use ($request) {
                $total = 0;
                $itemsData = [];

                // Jika form langsung mengirim table_id
                $tableId = $request->table_id;

                // Ambil items dari cart
                $cartItems = CartItem::where('user_id', auth()->id())->with('product')->get();

                foreach ($cartItems as $item) {
                    $subtotal = $item->product->price * $item->quantity;
                    $total += $subtotal;

                    $itemsData[] = [
                        'product_id' => $item->product_id,
                        'quantity' => $item->quantity,
                        'price' => $item->product->price,
                    ];
                }

                $order = Order::create([
                    'user_id' => auth()->id(),
                    'table_id' => $tableId,
                    'status' => 'pending',
                    'total_price' => $total
                ]);

                foreach ($itemsData as $data) {
                    $data['order_id'] = $order->id;
                    OrderItem::create($data);
                }

                // Hapus cart items
                CartItem::where('user_id', auth()->id())->delete();

                return $order;
            });

            return redirect()->route('orders.show', $order);

        } catch (\Exception $e) {
            // Log error
            \Log::error('Checkout failed: ' . $e->getMessage());

            // Redirect back dengan pesan error
            return redirect()->back()->with('error', 'Checkout gagal: ' . $e->getMessage());
        }
    }

    public function downloadReceipt($orderId)
    {
        $order = Order::with(['items.product', 'table', 'user'])->findOrFail($orderId);
        $pdf = Pdf::loadView('receipts.pdf', compact('order'));
        return $pdf->download('receipt-order-'.$order->id.'.pdf');
    }
}

<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Table;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('table')->where('user_id', auth()->id())->latest()->get();
        $products = Product::all();
        return view('user.orders.index', compact('orders', 'products'));
    }

    public function create(Request $request)
    {
        $tables = Table::all();
        $products = Product::all();
        $usedTableIds = Order::where('status', 'pending')->pluck('table_id')->toArray();
        $selectedProductId = $request->query('product_id'); // ambil dari URL

        return view('user.orders.create', compact('tables', 'products', 'usedTableIds', 'selectedProductId'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'table_id' => 'required|exists:tables,id',
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1'
        ]);

        DB::transaction(function () use ($request) {
            $total = 0;
            $itemsData = [];

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
                'user_id' => Auth::id(),
                'table_id' => $request->table_id,
                'total_price' => $total, // ubah ke total_price
                'status' => 'pending',
            ]);

            foreach ($itemsData as $data) {
                $data['order_id'] = $order->id;
                OrderItem::create($data);
            }
        });

        return redirect()->route('user.order')->with('success', 'Order placed successfully.');
    }

    public function downloadReceipt($orderId)
    {
        $order = Order::with(['items.product', 'table', 'user'])->findOrFail($orderId);
        $pdf = Pdf::loadView('receipts.pdf', compact('order'));
        return $pdf->download('receipt-order-'.$order->id.'.pdf');
    }
}

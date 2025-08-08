<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class UserOrderController extends Controller
{
    public function index()
    {
        $orders = \App\Models\Order::where('user_id', auth()->id())->with('table')->get();
        return view('user.orders.index', compact('orders'));
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
                if (empty($item['checked'])) continue;

                $menu = Menu::findOrFail($item['menu_id']);
                $subtotal = $menu->price * $item['quantity'];
                $total += $subtotal;

                $itemsData[] = [
                    'menu_name' => $menu->name,
                    'quantity' => $item['quantity'],
                    'price' => $menu->price,
                ];
            }

            $order = Order::create([
                'user_id' => Auth::id(),
                'table_number' => $request->table_number,
                'total_price' => $total,
                'status' => 'pending',
            ]);

            foreach ($itemsData as $data) {
                $data['order_id'] = $order->id;
                OrderItem::create($data);
            }
        });

        return redirect()->route('user.dashboard')->with('success', 'Order placed successfully.');
    }

    public function downloadReceipt($orderId)
    {
        $order = Order::with('items.product')->findOrFail($orderId);
        $pdf = Pdf::loadView('receipt.pdf', compact('order'));
        return $pdf->download('receipt-order-'.$order->id.'.pdf');
    }
}


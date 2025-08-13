<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Table;
use App\Models\Order;
use App\Models\CartItem;

class MenuController extends Controller
{
    public function index()
    {
        $products = Product::all();
        $tables = Table::where('is_available', true)->get();

        // Tambahkan data orders milik user yang login
        $orders = Order::where('user_id', auth()->id())
                      ->with(['orderItems.product', 'table'])
                      ->orderBy('created_at', 'desc')
                      ->get();

        // Ambil jumlah item di keranjang untuk badge
        $cartCount = CartItem::where('user_id', auth()->id())->sum('quantity');

        return view('user.orders.menu', compact('products', 'tables', 'orders', 'cartCount'));
    }
}

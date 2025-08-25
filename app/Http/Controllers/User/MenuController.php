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
        // Hanya tampilkan produk yang ada stoknya (optional optimization)
        $products = Product::where('stock', '>', 0)->get();
        $tables = Table::where('is_available', true)->get();

        // Tambahkan data orders milik user yang login (limit untuk performa)
        $orders = Order::where('user_id', auth()->id())
                      ->with(['orderItems.product', 'table'])
                      ->orderBy('created_at', 'desc')
                      ->limit(10) // Batasi hanya 10 order terakhir
                      ->get();

        // Ambil jumlah item di keranjang untuk badge
        $cartCount = CartItem::where('user_id', auth()->id())->sum('quantity');

        return view('user.orders.menu', compact('products', 'tables', 'orders', 'cartCount'));
    }
}

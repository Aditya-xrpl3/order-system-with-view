<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Table;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function add(Request $request, Product $product)
    {
        if ($product->stock <= 0) {
            return redirect()->back()->with('error', 'Produk sudah habis!');
        }

        $cartItem = CartItem::where('user_id', auth()->id())
                           ->where('product_id', $product->id)
                           ->first();

        if ($cartItem) {
            if ($cartItem->quantity >= $product->stock) {
                return redirect()->back()->with('error', 'Tidak bisa menambah, stok tidak mencukupi!');
            }
            $cartItem->increment('quantity');
        } else {
            CartItem::create([
                'user_id' => auth()->id(),
                'product_id' => $product->id,
                'quantity' => 1
            ]);
        }

        return redirect()->back()->with('success', 'Produk ditambahkan ke keranjang!');
    }

    public function index()
    {
        $cartItems = CartItem::where('user_id', auth()->id())
            ->with('product')
            ->get();

        $cartTotal = $cartItems->sum(function($item) {
            return $item->quantity * $item->product->price;
        });

        // Gunakan kolom number untuk meja
        $tables = \App\Models\Table::where('is_available', true)->get();

        return view('user.orders.cart', compact('cartItems', 'cartTotal', 'tables'));
    }

    public function update(Request $request, CartItem $cartItem)
    {
        // Pastikan cart item milik user yang login
        if ($cartItem->user_id !== auth()->id()) {
            abort(403);
        }

        $quantity = $request->input('quantity', 1);

        if ($quantity > $cartItem->product->stock) {
            return redirect()->back()->with('error', 'Kuantitas melebihi stok yang tersedia!');
        }

        if ($quantity <= 0) {
            $cartItem->delete();
        } else {
            $cartItem->update(['quantity' => $quantity]);
        }

        return redirect()->back()->with('success', 'Keranjang berhasil diupdate!');
    }

    public function remove(CartItem $cartItem)
    {
        // Pastikan cart item milik user yang login
        if ($cartItem->user_id !== auth()->id()) {
            abort(403);
        }

        $cartItem->delete();
        return redirect()->back()->with('success', 'Item dihapus dari keranjang!');
    }

    public function checkout(Request $request)
    {
        $request->validate([
            'table_id' => 'required|exists:tables,id'
        ]);

        $cartItems = CartItem::where('user_id', auth()->id())
                            ->with('product')
                            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->back()->with('error', 'Keranjang kosong!');
        }

        // Cek stok
        foreach ($cartItems as $item) {
            if ($item->quantity > $item->product->stock) {
                return redirect()->back()
                               ->with('error', "Stok {$item->product->name} tidak mencukupi!");
            }
        }

        $totalPrice = $cartItems->sum(function ($item) {
            return $item->quantity * $item->product->price;
        });

        // Buat order
        $order = Order::create([
            'user_id' => auth()->id(),
            'table_id' => $request->table_id,
            'total_price' => $totalPrice,
            'status' => 'pending'
        ]);

        // Buat order items dan kurangi stok
        foreach ($cartItems as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'price' => $item->product->price
            ]);

            // Kurangi stok
            $item->product->decrement('stock', $item->quantity);
        }

        // Hapus cart items
        CartItem::where('user_id', auth()->id())->delete();

        // Update status table
        Table::where('id', $request->table_id)->update(['is_available' => false]);

        return redirect()->route('orders.show', $order)
                        ->with('success', 'Pesanan berhasil dibuat!');
    }

    public function increase($productId)
    {
        $cartItem = CartItem::where('user_id', auth()->id())
            ->where('product_id', $productId)
            ->first();

        if ($cartItem) {
            $cartItem->increment('quantity');
        }

        return redirect()->route('cart.index');
    }

    public function decrease($productId)
    {
        $cartItem = CartItem::where('user_id', auth()->id())
            ->where('product_id', $productId)
            ->first();

        if ($cartItem) {
            if ($cartItem->quantity > 1) {
                $cartItem->decrement('quantity');
            } else {
                $cartItem->delete();
            }
        }

        return redirect()->route('cart.index');
    }

    public function removeByProductId($productId)
    {
        CartItem::where('user_id', auth()->id())
            ->where('product_id', $productId)
            ->delete();

        return redirect()->route('cart.index');
    }
}

<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function update(Request $request, $id)
    {
        $item = CartItem::findOrFail($id);
        $item->quantity = $request->quantity;
        $item->save();
        return back();
    }

    public function remove($id)
    {
        $item = CartItem::findOrFail($id);
        $item->delete();
        return back();
    }

    public function add($productId)
    {
        $user = auth()->user();
        $cartItem = \App\Models\CartItem::firstOrCreate(
            ['user_id' => $user->id, 'product_id' => $productId],
            ['quantity' => 0]
        );
        $cartItem->quantity += 1;
        $cartItem->save();

        return redirect()->route('cart.index')->with('success', 'Produk ditambahkan ke keranjang!');
    }

    public function index()
    {
        $cartItems = auth()->user()->cartItems()->with('product')->get();
        $cartTotal = $cartItems->sum(function($item) {
            return $item->product->price * $item->quantity;
        });

        return view('user.orders.cart', compact('cartItems', 'cartTotal'));
    }
}

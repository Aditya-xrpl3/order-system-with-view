<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Table;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    /**
     * Display the user's cart.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $cartItems = $request->user()->cartItems()->with('product')->get();

        $total = $cartItems->sum(function($item) {
            return $item->product->price * $item->quantity;
        });

        return response()->json([
            'items' => $cartItems,
            'total' => $total
        ]);
    }

    /**
     * Add a product to cart.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\JsonResponse
     */
    public function add(Request $request, Product $product)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string',
        ]);

        // Check stock
        if ($product->stock < $request->quantity) {
            return response()->json([
                'message' => 'Not enough stock available'
            ], 422);
        }

        // Check if product already in cart
        $cartItem = $request->user()->cartItems()
            ->where('product_id', $product->id)
            ->first();

        if ($cartItem) {
            // Update quantity
            $cartItem->quantity += $request->quantity;
            $cartItem->notes = $request->notes ?? $cartItem->notes;
            $cartItem->save();
        } else {
            // Create new cart item
            $cartItem = CartItem::create([
                'user_id' => $request->user()->id,
                'product_id' => $product->id,
                'quantity' => $request->quantity,
                'notes' => $request->notes,
            ]);
        }

        return response()->json([
            'message' => 'Product added to cart successfully',
            'item' => $cartItem->load('product')
        ]);
    }

    /**
     * Update cart item.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CartItem  $cartItem
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, CartItem $cartItem)
    {
        // Ensure the cart item belongs to the authenticated user
        if ($cartItem->user_id !== $request->user()->id) {
            return response()->json([
                'message' => 'Unauthorized access to cart item'
            ], 403);
        }

        $request->validate([
            'quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string',
        ]);

        // Check stock
        $product = $cartItem->product;
        if ($product->stock < $request->quantity) {
            return response()->json([
                'message' => 'Not enough stock available'
            ], 422);
        }

        $cartItem->update([
            'quantity' => $request->quantity,
            'notes' => $request->notes ?? $cartItem->notes,
        ]);

        return response()->json([
            'message' => 'Cart item updated successfully',
            'item' => $cartItem->fresh()->load('product')
        ]);
    }

    /**
     * Remove item from cart.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CartItem  $cartItem
     * @return \Illuminate\Http\JsonResponse
     */
    public function remove(Request $request, CartItem $cartItem)
    {
        // Ensure the cart item belongs to the authenticated user
        if ($cartItem->user_id !== $request->user()->id) {
            return response()->json([
                'message' => 'Unauthorized access to cart item'
            ], 403);
        }

        $cartItem->delete();

        return response()->json([
            'message' => 'Item removed from cart successfully'
        ]);
    }

    /**
     * Checkout the cart and create an order.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkout(Request $request)
    {
        $request->validate([
            'table_id' => 'required|exists:tables,id',
        ]);

        $user = $request->user();
        $cartItems = $user->cartItems()->with('product')->get();

        if ($cartItems->isEmpty()) {
            return response()->json([
                'message' => 'Cart is empty'
            ], 422);
        }

        try {
            return DB::transaction(function () use ($request, $user, $cartItems) {
                // Create order
                $order = Order::create([
                    'user_id' => $user->id,
                    'table_id' => $request->table_id,
                    'status' => 'pending',
                    'total_price' => 0, // Will be calculated
                ]);

                $totalPrice = 0;

                // Add items to order
                foreach ($cartItems as $cartItem) {
                    $product = $cartItem->product;

                    // Check stock
                    if ($product->stock < $cartItem->quantity) {
                        throw new \Exception("Not enough stock for {$product->name}");
                    }

                    // Decrease product stock
                    $product->decrement('stock', $cartItem->quantity);

                    // Create order item
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                        'quantity' => $cartItem->quantity,
                        'notes' => $cartItem->notes,
                        'price' => $product->price,
                    ]);

                    $totalPrice += $product->price * $cartItem->quantity;
                }

                // Update table availability
                $table = Table::findOrFail($request->table_id);
                $table->update(['is_available' => false]);

                // Update order total
                $order->update(['total_price' => $totalPrice]);

                // Clear cart
                $user->cartItems()->delete();

                return response()->json([
                    'message' => 'Order created successfully',
                    'order' => $order->load('items.product'),
                ], 201);
            });
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to checkout cart',
                'error' => $e->getMessage(),
            ], 422);
        }
    }
}

<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;

class ReceiptController extends Controller
{
    /**
     * Display receipt information for an order.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Order $order)
    {
        // Ensure the user can access this order
        $this->authorize('view', $order);

        $order->load(['items.product', 'user', 'table']);

        return response()->json([
            'order' => $order,
            'items' => $order->items,
            'user' => $order->user,
            'table' => $order->table,
            'total' => $order->total_price,
            'date' => $order->created_at->format('Y-m-d H:i:s'),
        ]);
    }

    /**
     * Generate a PDF receipt for an order.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function generatePdf(Order $order)
    {
        // Ensure the user can access this order
        $this->authorize('view', $order);

        $order->load(['items.product', 'user', 'table']);

        $data = [
            'order' => $order,
            'items' => $order->items,
            'user' => $order->user,
            'table' => $order->table,
            'total' => $order->total_price,
            'date' => $order->created_at->format('Y-m-d H:i:s'),
        ];

        $pdf = PDF::loadView('receipts.pdf', $data);

        return $pdf->download("receipt-order-{$order->id}.pdf");
    }
}

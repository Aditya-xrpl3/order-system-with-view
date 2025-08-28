<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Receipt;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ReceiptController extends Controller
{
    public function show(Order $order)
    {
        $order->load(['orderItems.product', 'user', 'table']);
        // Generate PDF/PNG receipt logic here
        return view('receipts.show', compact('order'));
    }

    public function index()
    {
        $receipts = Receipt::all();
        return view('receipts.index', compact('receipts'));
    }

    public function destroy(Receipt $receipt)
    {
        $receipt->delete();
        return redirect()->route('receipts.index')->with('success', 'Receipt deleted!');
    }

    public function downloadPdf(Order $order)
    {
        $order->load(['orderItems.product', 'user', 'table']);
        $pdf = Pdf::loadView('receipts.pdf', compact('order'));
        return $pdf->download('receipt-' . $order->id . '.pdf');
    }
}

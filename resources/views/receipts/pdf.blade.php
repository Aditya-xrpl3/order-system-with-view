{{-- filepath: resources/views/receipts/pdf.blade.php --}}
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Struk Order #{{ $order->id }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px;}
        th, td { border: 1px solid #333; padding: 6px 8px; text-align: left; }
        th { background: #eee; }
        .total { text-align: right; font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Struk Order</h2>
        <p>No. Order: <strong>#{{ $order->id }}</strong></p>
        <p>Tanggal: {{ $order->created_at->format('d-m-Y H:i') }}</p>
        <p>Meja: <strong>{{ $order->table->number ?? '-' }}</strong></p>
    </div>
    <table>
        <thead>
            <tr>
                <th>Produk</th>
                <th>Qty</th>
                <th>Harga</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
            <tr>
                <td>{{ $item->product->name }}</td>
                <td>{{ $item->quantity }}</td>
                <td>Rp{{ number_format($item->product->price,0,',','.') }}</td>
                <td>Rp{{ number_format($item->subtotal,0,',','.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <p class="total">Total: Rp{{ number_format($order->total_price, 0, ',', '.') }}</p>
    <p>Terima kasih telah berbelanja!</p>
</body>
</html>

{{-- filepath: resources/views/receipts/pdf.blade.php --}}
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Struk Order #{{ $order->id }}</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        .receipt {
            max-width: 300px;
            margin: 0 auto;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px dashed #ccc;
        }
        .header h1 {
            font-size: 18px;
            margin: 0 0 5px 0;
        }
        .header p {
            font-size: 12px;
            margin: 2px 0;
        }
        .info {
            margin-bottom: 15px;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            font-size: 12px;
            margin-bottom: 5px;
        }
        .items {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            font-size: 12px;
        }
        .items th {
            text-align: left;
            padding-bottom: 5px;
            border-bottom: 1px solid #eee;
        }
        .items td {
            padding: 5px 0;
        }
        .total {
            text-align: right;
            font-weight: bold;
            font-size: 14px;
            margin-top: 10px;
            padding-top: 10px;
            border-top: 1px solid #eee;
        }
        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 12px;
            padding-top: 15px;
            border-top: 1px dashed #ccc;
        }
    </style>
</head>
<body>
    <div class="receipt">
        <div class="header">
            <h1>{{ config('app.name', 'Restaurant') }}</h1>
            <p>Jl. Contoh No. 123, Jakarta</p>
            <p>Telp: 021-123456</p>
        </div>

        <div class="info">
            <div class="info-row">
                <span><strong>No. Order:</strong></span>
                <span>{{ $order->id }}</span>
            </div>
            <div class="info-row">
                <span><strong>Customer:</strong></span>
                <span>{{ $order->user->name ?? 'Customer' }}</span>
            </div>
            <div class="info-row">
                <span><strong>Tanggal:</strong></span>
                <span>{{ $order->created_at->format('d-m-Y H:i') }}</span>
            </div>
            @if($order->table)
            <div class="info-row">
                <span><strong>Meja:</strong></span>
                <span>{{ $order->table->name ?? '-' }}</span>
            </div>
            @endif
        </div>

        <table class="items">
            <thead>
                <tr>
                    <th width="50%">Menu</th>
                    <th width="15%">Qty</th>
                    <th width="35%" style="text-align: right;">Harga</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->orderItems as $item)
                <tr>
                    <td>{{ $item->product->name }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td style="text-align: right;">Rp{{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="total">
            Total: Rp{{ number_format($order->total_price, 0, ',', '.') }}
        </div>

        <div class="footer">
            <p><strong>Terima kasih atas pesanan Anda!</strong></p>
            <p>Silakan kunjungi kami kembali</p>
        </div>
    </div>
</body>
</html>

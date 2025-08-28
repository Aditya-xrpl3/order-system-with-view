{{-- filepath: resources/views/receipts/show.blade.php --}}
<x-app-layout>
    <div class="py-6 max-w-md mx-auto">
        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            <!-- Header Receipt -->
            <div class="bg-blue-600 text-white text-center py-4">
                <h1 class="text-2xl font-bold">{{ config('app.name', 'Restaurant') }}</h1>
                <p class="text-sm">Jl. Contoh No. 123, Jakarta</p>
                <p class="text-sm">Telp: 021-123456</p>
            </div>

            <!-- Receipt Content -->
            <div class="p-6">
                <div class="text-center mb-4">
                    <h2 class="text-xl font-semibold">Struk Pembayaran</h2>
                    <p class="text-gray-600 text-sm">{{ $order->created_at->format('d-m-Y H:i') }}</p>
                </div>

                <div class="border-t border-b border-gray-200 py-2 mb-4">
                    <div class="flex justify-between">
                        <span class="font-medium">No. Order:</span>
                        <span>{{ $order->id }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="font-medium">Customer:</span>
                        <span>{{ $order->user->name ?? 'Customer' }}</span>
                    </div>
                    @if($order->table)
                    <div class="flex justify-between">
                        <span class="font-medium">Meja:</span>
                        <span>{{ $order->table->number ?? '-' }}</span>
                    </div>
                    @endif
                </div>

                <!-- Order Items -->
                <table class="w-full mb-4">
                    <thead class="border-b">
                        <tr class="text-left">
                            <th class="pb-2">Menu</th>
                            <th class="pb-2 text-center">Qty</th>
                            <th class="pb-2 text-right">Harga</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->orderItems as $item)
                        <tr>
                            <td class="py-2">{{ $item->product->name }}</td>
                            <td class="py-2 text-center">{{ $item->quantity }}</td>
                            <td class="py-2 text-right">Rp{{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- Total -->
                <div class="border-t border-gray-200 pt-2">
                    <div class="flex justify-between font-bold text-lg">
                        <span>Total:</span>
                        <span>Rp{{ number_format($order->total_price, 0, ',', '.') }}</span>
                    </div>
                </div>

                <!-- Footer -->
                <div class="mt-6 text-center text-gray-600">
                    <p class="font-medium">Terima kasih atas pesanan Anda!</p>
                    <p class="text-sm mt-2">Silakan kunjungi kami kembali</p>

                    <!-- Download button -->
                    <div class="mt-4">
                        <a href="{{ route('receipt.download', $order->id) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:border-blue-700 focus:ring focus:ring-blue-200 active:bg-blue-600 transition">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Download PDF
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

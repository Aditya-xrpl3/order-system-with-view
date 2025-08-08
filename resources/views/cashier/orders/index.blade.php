{{-- filepath: resources/views/cashier/orders/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ ('Daftar Order') }}
        </h2>
    </x-slot>
    <div class="py-6">
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-200 rounded shadow">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2">No</th>
                        <th class="px-4 py-2">Nama Pemesan</th>
                        <th class="px-4 py-2">Meja</th>
                        <th class="px-4 py-2">Total</th>
                        <th class="px-4 py-2">Status</th>
                        <th class="px-4 py-2">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                    <tr class="border-t">
                        <td class="px-4 py-2">{{ $loop->iteration }}</td>
                        <td class="px-4 py-2">{{ $order->user->name ?? '-' }}</td>
                        <td class="px-4 py-2">{{ $order->table->number ?? '-' }}</td>
                        <td class="px-4 py-2">Rp{{ number_format($order->total_price, 0, ',', '.') }}</td>
                        <td class="px-4 py-2">
                            <span class="px-2 py-1 rounded text-xs {{ $order->status == 'completed' ? 'bg-green-200 text-green-800' : 'bg-yellow-200 text-yellow-800' }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-2">
                            <a href="{{ route('cashier.orders.show', $order) }}" class="text-blue-600 hover:underline">Detail</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-4 py-2 text-center text-gray-500">Belum ada order.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-8 grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="text-center">
                <div class="text-lg font-bold">Total Order</div>
                <div class="text-2xl">{{ $totalOrders }}</div>
            </div>
            <div class="text-center">
                <div class="text-lg font-bold">Order Selesai</div>
                <div class="text-2xl text-green-600">{{ $completedOrders }}</div>
            </div>
            <div class="text-center">
                <div class="text-lg font-bold">Order Pending</div>
                <div class="text-2xl text-yellow-600">{{ $pendingOrders }}</div>
            </div>
            <div class="text-center">
                <div class="text-lg font-bold">Pendapatan</div>
                <div class="text-2xl text-blue-600">Rp{{ number_format($totalRevenue,0,',','.') }}</div>
            </div>
        </div>

        <div class="flex justify-center my-8">
            <canvas id="orderChart" width="400" height="250"></canvas>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
        const ctx = document.getElementById('orderChart').getContext('2d');
        const chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Completed', 'Pending'],
                datasets: [{
                    label: 'Jumlah Order',
                    data: [{{ $completedOrders }}, {{ $pendingOrders }}],
                    backgroundColor: ['#16a34a', '#facc15'],
                }]
            },
            options: {
                responsive: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        precision: 0
                    }
                }
            }
        });
        </script>

        <div class="bg-white rounded shadow p-6 my-8">
            <h3 class="font-bold mb-4">Grafik Order 7 Hari Terakhir</h3>
            <canvas id="orderLineChart" height="80"></canvas>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
        const ctxLine = document.getElementById('orderLineChart').getContext('2d');
        const chartLine = new Chart(ctxLine, {
            type: 'line',
            data: {
                labels: {!! json_encode($dates) !!},
                datasets: [
                    {
                        label: 'Completed',
                        data: {!! json_encode($completedData) !!},
                        borderColor: '#16a34a',
                        backgroundColor: 'rgba(22,163,74,0.1)',
                        fill: true,
                        tension: 0.3
                    },
                    {
                        label: 'Pending',
                        data: {!! json_encode($pendingData) !!},
                        borderColor: '#facc15',
                        backgroundColor: 'rgba(250,204,21,0.1)',
                        fill: true,
                        tension: 0.3
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: true }
                },
                scales: {
                    y: { beginAtZero: true, precision: 0 }
                }
            }
        });
        </script>
    </div>
</x-app-layout>

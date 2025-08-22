<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight flex items-center">
                <svg class="w-6 h-6 mr-2 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z"/>
                    <path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z"/>
                </svg>
                {{ __('Statistik & Laporan') }}
            </h2>
            <div class="flex items-center space-x-4">
                <select id="timeframe" name="timeframe" class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
                    <option value="today">Hari Ini</option>
                    <option value="week">Minggu Ini</option>
                    <option value="month">Bulan Ini</option>
                    <option value="year">Tahun Ini</option>
                </select>
                <a id="exportBtn" href="{{ route('statistics.export') }}" class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Export Excel
                </a>

                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const timeframeSelect = document.getElementById('timeframe');
                        const exportBtn = document.getElementById('exportBtn');

                        // Update export link with timeframe parameter when dropdown changes
                        timeframeSelect.addEventListener('change', function() {
                            const baseUrl = "{{ route('statistics.export') }}";
                            const timeframe = timeframeSelect.value;
                            exportBtn.href = `${baseUrl}?timeframe=${timeframe}`;
                        });

                        // Initialize with selected timeframe
                        const timeframe = timeframeSelect.value;
                        exportBtn.href = `{{ route('statistics.export') }}?timeframe=${timeframe}`;
                    });
                </script>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">


            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl p-6 text-white transform hover:scale-105 transition-transform">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-green-100 text-sm">Pendapatan Hari Ini</p>
                            <p class="text-2xl font-bold">Rp{{ number_format($todayRevenue ?? 100000, 0, ',', '.') }}</p>
                            <p class="text-green-200 text-xs mt-1">+12% dari kemarin</p>
                        </div>
                        <div class="bg-white bg-opacity-20 p-3 rounded-lg">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4zM18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-6 text-white transform hover:scale-105 transition-transform">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-blue-100 text-sm">Total Pesanan</p>
                            <p class="text-2xl font-bold">{{ $totalOrders ?? 45 }}</p>
                            <p class="text-blue-200 text-xs mt-1">+8% dari kemarin</p>
                        </div>
                        <div class="bg-white bg-opacity-20 p-3 rounded-lg">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl p-6 text-white transform hover:scale-105 transition-transform">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-purple-100 text-sm">Rata-rata Order</p>
                            <p class="text-2xl font-bold">Rp{{ number_format($averageOrder ?? 25000, 0, ',', '.') }}</p>
                            <p class="text-purple-200 text-xs mt-1">+5% dari kemarin</p>
                        </div>
                        <div class="bg-white bg-opacity-20 p-3 rounded-lg">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3 3a1 1 0 000 2v8a2 2 0 002 2h2.586l-1.293 1.293a1 1 0 101.414 1.414L10 15.414l2.293 2.293a1 1 0 001.414-1.414L12.414 15H15a2 2 0 002-2V5a1 1 0 100-2H3zm11.707 4.707a1 1 0 00-1.414-1.414L10 9.586 8.707 8.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl p-6 text-white transform hover:scale-105 transition-transform">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-orange-100 text-sm">Menu Terlaris</p>
                            <p class="text-xl font-bold">Nasi Goreng</p>
                            <p class="text-orange-200 text-xs mt-1">15 porsi terjual</p>
                        </div>
                        <div class="bg-white bg-opacity-20 p-3 rounded-lg">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M12 7a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0V8.414l-4.293 4.293a1 1 0 01-1.414 0L8 10.414l-4.293 4.293a1 1 0 01-1.414-1.414l5-5a1 1 0 011.414 0L11 10.586 14.586 7H12z"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Charts Section --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Grafik Penjualan 7 Hari Terakhir</h3>
                        <div class="flex space-x-2">
                            <span class="inline-flex items-center px-2 py-1 rounded text-xs bg-green-100 text-green-800">
                                <span class="w-2 h-2 bg-green-500 rounded-full mr-1"></span>
                                Completed
                            </span>
                            <span class="inline-flex items-center px-2 py-1 rounded text-xs bg-yellow-100 text-yellow-800">
                                <span class="w-2 h-2 bg-yellow-500 rounded-full mr-1"></span>
                                Pending
                            </span>
                        </div>
                    </div>
                    <div class="h-64 bg-gray-50 rounded-lg">
                        <canvas id="salesChart" width="400" height="200"></canvas>
                    </div>

                    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const ctx = document.getElementById('salesChart').getContext('2d');

                            // Tanggal untuk 7 hari terakhir
                            const last7Days = [];
                            for (let i = 6; i >= 0; i--) {
                                const d = new Date();
                                d.setDate(d.getDate() - i);
                                last7Days.push(d.toLocaleDateString('id-ID', {day: '2-digit', month: 'short'}));
                            }

                            // Data dummy - akan diganti dengan data sebenarnya dari controller
                            const completedOrders = [4, 7, 5, 8, 10, 6, 9];
                            const pendingOrders = [2, 1, 3, 1, 2, 4, 2];

                            const salesChart = new Chart(ctx, {
                                type: 'bar',
                                data: {
                                    labels: last7Days,
                                    datasets: [
                                        {
                                            label: 'Completed',
                                            data: completedOrders,
                                            backgroundColor: 'rgba(16, 185, 129, 0.6)', // Warna hijau
                                            borderColor: 'rgb(16, 185, 129)',
                                            borderWidth: 1
                                        },
                                        {
                                            label: 'Pending',
                                            data: pendingOrders,
                                            backgroundColor: 'rgba(245, 158, 11, 0.6)', // Warna oranye
                                            borderColor: 'rgb(245, 158, 11)',
                                            borderWidth: 1
                                        }
                                    ]
                                },
                                options: {
                                    responsive: true,
                                    maintainAspectRatio: false,
                                    scales: {
                                        y: {
                                            beginAtZero: true,
                                            ticks: {
                                                precision: 0
                                            }
                                        }
                                    }
                                }
                            });
                        });
                    </script>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Menu Terlaris</h3>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-4 bg-gradient-to-r from-blue-50 to-blue-100 rounded-lg">
                            <div class="flex items-center">
                                <div class="bg-blue-500 text-white rounded-full w-8 h-8 flex items-center justify-center text-sm font-bold mr-3">1</div>
                                <div>
                                    <p class="font-medium text-gray-900">Nasi Goreng</p>
                                    <p class="text-sm text-gray-600">15 porsi</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-blue-600">Rp300.000</p>
                                <p class="text-sm text-gray-500">Total</p>
                            </div>
                        </div>

                        <div class="flex items-center justify-between p-4 bg-gradient-to-r from-green-50 to-green-100 rounded-lg">
                            <div class="flex items-center">
                                <div class="bg-green-500 text-white rounded-full w-8 h-8 flex items-center justify-center text-sm font-bold mr-3">2</div>
                                <div>
                                    <p class="font-medium text-gray-900">Es Teh</p>
                                    <p class="text-sm text-gray-600">20 gelas</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-green-600">Rp100.000</p>
                                <p class="text-sm text-gray-500">Total</p>
                            </div>
                        </div>

                        <div class="flex items-center justify-between p-4 bg-gradient-to-r from-yellow-50 to-yellow-100 rounded-lg">
                            <div class="flex items-center">
                                <div class="bg-yellow-500 text-white rounded-full w-8 h-8 flex items-center justify-center text-sm font-bold mr-3">3</div>
                                <div>
                                    <p class="font-medium text-gray-900">Mie Goreng</p>
                                    <p class="text-sm text-gray-600">12 porsi</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-yellow-600">Rp180.000</p>
                                <p class="text-sm text-gray-500">Total</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tables Summary --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                    </svg>
                    Status Meja Hari Ini
                </h3>
                <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                    @foreach(\App\Models\Table::all() as $table)
                    <form action="{{ route('tables.setEmpty', $table->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full bg-gray-50 rounded-lg p-4 text-center {{ $table->is_available ? 'border-2 border-green-200 bg-green-50 hover:bg-green-100' : 'border-2 border-gray-200 hover:bg-gray-100' }} transition-colors">
                            <div class="text-lg font-bold {{ $table->is_available ? 'text-green-600' : 'text-gray-400' }}">Meja {{ $table->number }}</div>
                            <div class="text-xs {{ $table->is_available ? 'text-green-500' : 'text-gray-400' }}">
                                {{ $table->is_available ? 'Terpakai' : 'Kosong' }}
                            </div>
                        </button>
                    </form>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

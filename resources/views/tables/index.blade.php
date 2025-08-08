{{-- filepath: resources/views/tables/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Daftar Meja') }}
        </h2>
    </x-slot>
    <div class="py-6">
        <a href="{{ route('tables.create') }}" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded shadow transition mb-4">
            + Tambah Meja
        </a>
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-200 rounded shadow">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2">No</th>
                        <th class="px-4 py-2">Nama Meja</th>
                        <th class="px-4 py-2">Status</th>
                        <th class="px-4 py-2">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tables as $table)
                    <tr class="border-t">
                        <td class="px-4 py-2">{{ $loop->iteration }}</td>
                        <td class="px-4 py-2">{{ $table->number }}</td>
                        <td class="px-4 py-2">
                            <span class="px-2 py-1 rounded text-xs {{ $table->status == 'available' ? 'bg-green-200 text-green-800' : 'bg-red-200 text-red-800' }}">
                                {{ ucfirst($table->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-2 space-x-2">
                            <a href="{{ route('tables.edit', $table) }}" class="text-blue-600 hover:underline">Edit</a>
                            <form action="{{ route('tables.destroy', $table) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline" onclick="return confirm('Yakin hapus meja?')">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-4 py-2 text-center text-gray-500">Belum ada meja.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>

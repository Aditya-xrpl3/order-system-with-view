{{-- filepath: resources/views/admin/users/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manajemen User') }}
        </h2>
    </x-slot>
    <div class="py-6">
        <a href="{{ route('users.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded">Tambah User</a>
        <table class="min-w-full mt-4 bg-white">
            <thead>
                <tr>
                    <th class="px-4 py-2">Nama</th>
                    <th class="px-4 py-2">Email</th>
                    <th class="px-4 py-2">Role</th>
                    <th class="px-4 py-2">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr>
                    <td class="border px-4 py-2">{{ $user->name }}</td>
                    <td class="border px-4 py-2">{{ $user->email }}</td>
                    <td class="border px-4 py-2">{{ $user->role }}</td>
                    <td class="border px-4 py-2">
                        <a href="{{ route('users.edit', $user) }}" class="text-blue-600">Edit</a>
                        <form action="{{ route('users.destroy', $user) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button class="text-red-600" onclick="return confirm('Yakin hapus user?')">Hapus</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>

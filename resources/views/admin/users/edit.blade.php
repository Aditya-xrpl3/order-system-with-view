{{-- filepath: resources/views/admin/users/edit.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit User') }}
        </h2>
    </x-slot>
    <div class="py-6">
        <form action="{{ route('admin.users.update', $user) }}" method="POST" class="max-w-md mx-auto bg-white p-6 rounded shadow">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label class="block">Nama</label>
                <input type="text" name="name" value="{{ $user->name }}" class="w-full border rounded px-2 py-1" required>
            </div>
            <div class="mb-4">
                <label class="block">Email</label>
                <input type="email" name="email" value="{{ $user->email }}" class="w-full border rounded px-2 py-1" required>
            </div>
            <div class="mb-4">
                <label class="block">Role</label>
                <select name="role" class="w-full border rounded px-2 py-1">
                    <option value="admin" @if($user->role=='admin') selected @endif>Admin</option>
                    <option value="cashier" @if($user->role=='cashier') selected @endif>Cashier</option>
                    <option value="user" @if($user->role=='user') selected @endif>User</option>
                </select>
            </div>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Update</button>
        </form>
    </div>
</x-app-layout>

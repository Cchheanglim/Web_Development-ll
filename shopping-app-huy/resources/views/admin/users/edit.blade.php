@extends('layouts.app')
@section('title', 'Edit user')

@section('content')
    <h1 class="text-2xl font-bold mb-6 mt-6">Edit user</h1>

    <form action="{{ route('admin.users.update', $user) }}" method="POST"
          class="bg-white border rounded-lg p-6 max-w-lg space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-sm font-medium mb-1">Name</label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                   class="w-full border rounded-md px-3 py-2">
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Email</label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                   class="w-full border rounded-md px-3 py-2">
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Role</label>
            <select name="role" class="w-full border rounded-md px-3 py-2">
                <option value="buyer" @selected($user->role === 'buyer')>Buyer</option>
                <option value="seller" @selected($user->role === 'seller')>Seller</option>
                <option value="admin" @selected($user->role === 'admin')>Admin</option>
            </select>
        </div>

        <button type="submit" class="bg-orange-600 text-white px-5 py-2.5 rounded-md hover:bg-orange-700">
            Save changes
        </button>
    </form>
@endsection

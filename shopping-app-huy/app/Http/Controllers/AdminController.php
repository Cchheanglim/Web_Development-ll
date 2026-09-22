<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    /** Simple admin overview with headline counts. */
    public function dashboard()
    {
        $stats = [
            'users' => User::count(),
            'sellers' => User::where('role', User::ROLE_SELLER)->count(),
            'buyers' => User::where('role', User::ROLE_BUYER)->count(),
            'products' => Product::count(),
            'orders' => Order::count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }

    // ---------- Users CRUD ----------

    public function users()
    {
        $users = User::latest()->paginate(15);

        return view('admin.users.index', compact('users'));
    }

    public function editUser(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function updateUser(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
            'role' => ['required', 'in:buyer,seller,admin'],
        ]);

        $user->update($validated);

        return redirect()->route('admin.users.index')->with('status', 'User updated.');
    }

    public function destroyUser(User $user)
    {
        abort_if($user->id === request()->user()->id, 403, "You can't delete your own admin account.");

        $user->delete();

        return redirect()->route('admin.users.index')->with('status', 'User deleted.');
    }

    // ---------- Products CRUD (admin can moderate any listing) ----------

    public function products()
    {
        $products = Product::with(['seller', 'images'])->latest()->paginate(15);

        return view('admin.products.index', compact('products'));
    }

    public function destroyProduct(Product $product)
    {
        foreach ($product->images as $image) {
            Storage::disk('public')->delete($image->image_path);
        }

        $product->delete();

        return redirect()->route('admin.products.index')->with('status', 'Product removed.');
    }
}

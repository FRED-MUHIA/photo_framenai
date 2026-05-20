<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = User::query()
            ->where('role', 'customer')
            ->withCount('orders')
            ->latest()
            ->paginate(20);

        return view('admin.customers.index', compact('customers'));
    }

    public function show(User $customer)
    {
        $customer->load(['orders.items', 'artworks']);

        return view('admin.customers.show', compact('customer'));
    }

    public function update(Request $request, User $customer)
    {
        $validated = $request->validate([
            'role' => ['required', 'in:customer,admin'],
        ]);

        $customer->update(['role' => $validated['role']]);

        return back()->with('status', 'Customer role updated.');
    }
}

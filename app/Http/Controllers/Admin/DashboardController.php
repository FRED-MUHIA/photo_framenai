<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Artwork;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $stats = [
            'orders' => Order::query()->count(),
            'revenue' => (float) Order::query()->whereIn('payment_status', ['paid'])->sum('total_amount'),
            'customers' => User::query()->where('role', 'customer')->count(),
            'products' => Product::query()->count(),
            'pending_artworks' => Artwork::query()->where('status', 'pending')->count(),
        ];

        $recentOrders = Order::query()->latest()->take(10)->get();

        return view('admin.dashboard', compact('stats', 'recentOrders'));
    }
}

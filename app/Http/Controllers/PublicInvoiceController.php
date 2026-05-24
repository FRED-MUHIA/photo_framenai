<?php

namespace App\Http\Controllers;

use App\Models\Order;

class PublicInvoiceController extends Controller
{
    public function show(Order $order)
    {
        $order->load('items');

        return view('dashboard.invoice', compact('order'));
    }
}

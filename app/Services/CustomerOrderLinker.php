<?php

namespace App\Services;

use App\Models\Order;
use App\Models\User;

class CustomerOrderLinker
{
    public function linkByEmail(User $user): int
    {
        return Order::query()
            ->whereNull('user_id')
            ->whereRaw('lower(customer_email) = ?', [strtolower($user->email)])
            ->update(['user_id' => $user->id]);
    }
}

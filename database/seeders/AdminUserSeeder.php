<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::query()->updateOrCreate(
            ['email' => 'admin@naiprinters.co.ke'],
            [
                'name' => 'NaiPrinters Admin',
                'phone' => '+254700000000',
                'role' => 'admin',
                'preferred_locale' => 'en',
                'email_verified_at' => now(),
                'password' => Hash::make('-robba8688@Fred'),
            ],
        );
    }
}

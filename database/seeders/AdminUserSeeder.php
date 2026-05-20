<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $email = env('ADMIN_EMAIL', 'admin@naiprinters.co.ke');
        $username = env('ADMIN_USERNAME', 'Govoh');
        $password = env('ADMIN_PASSWORD', 'robba8688@Fred');

        User::query()
            ->where('username', $username)
            ->where('email', '!=', $email)
            ->update(['username' => null]);

        User::query()->updateOrCreate(
            ['email' => $email],
            [
                'name' => 'NaiPrinters Admin',
                'username' => $username,
                'phone' => '+254700000000',
                'role' => 'admin',
                'preferred_locale' => 'en',
                'email_verified_at' => now(),
                'password' => Hash::make($password),
            ],
        );
    }
}

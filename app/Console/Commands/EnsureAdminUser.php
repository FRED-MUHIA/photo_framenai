<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class EnsureAdminUser extends Command
{
    protected $signature = 'admin:ensure
        {--email= : Admin email address}
        {--password= : Admin password}
        {--name=NaiPrinters Admin : Admin display name}';

    protected $description = 'Create or update the admin login account.';

    public function handle(): int
    {
        $email = (string) ($this->option('email') ?: env('ADMIN_EMAIL', 'admin@naiprinters.co.ke'));
        $password = (string) ($this->option('password') ?: env('ADMIN_PASSWORD', '-robba8688@Fred'));
        $name = (string) $this->option('name');

        if ($email === '' || $password === '') {
            $this->error('Admin email and password are required.');

            return self::FAILURE;
        }

        $user = User::query()->updateOrCreate(
            ['email' => $email],
            [
                'name' => $name,
                'phone' => '+254700000000',
                'role' => 'admin',
                'preferred_locale' => 'en',
                'email_verified_at' => now(),
                'password' => Hash::make($password),
            ],
        );

        $this->info("Admin user ready: {$user->email}");

        return self::SUCCESS;
    }
}

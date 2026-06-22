<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class EnsureAdminUser extends Command
{
    protected $signature = 'admin:ensure
        {--email= : Admin email address}
        {--username= : Admin username}
        {--password= : Admin password}
        {--name=Nai Printers Admin : Admin display name}';

    protected $description = 'Create or update the admin login account.';

    public function handle(): int
    {
        $email = (string) ($this->option('email') ?: env('ADMIN_EMAIL', 'admin@naiprinters.co.ke'));
        $username = (string) ($this->option('username') ?: env('ADMIN_USERNAME', 'Govoh'));
        $password = (string) ($this->option('password') ?: env('ADMIN_PASSWORD', 'robba8688@Fred'));
        $name = (string) $this->option('name');

        if ($email === '' || $username === '' || $password === '') {
            $this->error('Admin email, username and password are required.');

            return self::FAILURE;
        }

        User::query()
            ->where('username', $username)
            ->where('email', '!=', $email)
            ->update(['username' => null]);

        $user = User::query()->updateOrCreate(
            ['email' => $email],
            [
                'name' => $name,
                'username' => $username,
                'phone' => '+254700000000',
                'role' => 'admin',
                'preferred_locale' => 'en',
                'email_verified_at' => now(),
                'password' => Hash::make($password),
            ],
        );

        $this->info("Admin user ready: {$user->username}");

        return self::SUCCESS;
    }
}

<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class RecoverAccount extends Command
{
    protected $signature = 'account:recover {email : The account email address}';

    protected $description = 'Reset an account password or create a customer account.';

    public function handle(): int
    {
        $email = Str::lower(trim((string) $this->argument('email')));

        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->error('Enter a valid email address.');

            return self::FAILURE;
        }

        $user = User::query()->whereRaw('LOWER(email) = ?', [$email])->first();

        if ($user) {
            $this->line("Account found with role: {$user->role}.");

            if (! $this->confirm('Reset this account password?')) {
                return self::SUCCESS;
            }
        } else {
            $this->warn('No account exists for that email.');

            if (! $this->confirm('Create a customer account?')) {
                return self::FAILURE;
            }
        }

        $password = $this->promptPassword();

        if ($password === null) {
            return self::FAILURE;
        }

        if ($user) {
            $user->password = Hash::make($password);
            $user->save();
            $this->info('Account password reset.');

            return self::SUCCESS;
        }

        $name = trim((string) $this->ask('Name'));

        if ($name === '') {
            $this->error('A name is required to create the account.');

            return self::FAILURE;
        }

        User::query()->create([
            'name' => $name,
            'email' => $email,
            'role' => 'customer',
            'password' => Hash::make($password),
        ]);

        $this->info('Customer account created. You can now log in.');

        return self::SUCCESS;
    }

    private function promptPassword(): ?string
    {
        $password = $this->secret('New password (at least 12 characters)');
        $confirmation = $this->secret('Confirm new password');

        if (! is_string($password) || strlen($password) < 12 || $password !== $confirmation) {
            $this->error('Passwords must match and contain at least 12 characters.');

            return null;
        }

        return $password;
    }
}

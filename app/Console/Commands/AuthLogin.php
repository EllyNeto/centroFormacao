<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;

class AuthLogin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * Usage: php artisan auth:login {email} {password} {--remember}
     */
    protected $signature = 'auth:login {email : The user email address} {password : The user password} {--remember : Remember the user}';

    /**
     * The console command description.
     */
    protected $description = 'Attempt to authenticate a user via email and password and output success or failure.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        $password = $this->argument('password');
        $remember = $this->option('remember');

        $credentials = ['email' => $email, 'password' => $password];

        if (Auth::attempt($credentials, $remember)) {
            $this->info('Authentication successful.');
            $this->line('User ID: ' . Auth::id());
            return 0;
        }

        $this->error('Authentication failed. Invalid credentials.');
        return 1;
    }
}

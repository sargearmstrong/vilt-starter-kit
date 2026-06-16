<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

class CreateAdminUser extends Command
{
    protected $signature = 'app:create-admin
        {--name= : Admin user name}
        {--email= : Admin user email address}
        {--password= : Admin user password. Omit to be prompted.}
        {--super : Assign the super-admin role instead of admin}';

    protected $description = 'Create or update an admin user and assign the starter admin role.';

    public function handle(): int
    {
        $name = $this->option('name') ?: $this->ask('Name', 'Admin User');
        $email = $this->option('email') ?: $this->ask('Email');
        $password = $this->option('password') ?: $this->secret('Password');

        $validator = Validator::make([
            'name' => $name,
            'email' => $email,
            'password' => $password,
        ], [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'password' => array_filter(['required', Password::defaults()]),
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }

            return self::FAILURE;
        }

        $role = $this->option('super') ? 'super-admin' : 'admin';

        $user = User::query()->updateOrCreate(
            ['email' => Str::lower((string) $email)],
            [
                'name' => $name,
                'password' => $password,
                'email_verified_at' => now(),
            ],
        );

        $user->syncRoles([$role]);

        $this->info("Admin user {$user->email} is ready with the {$role} role.");

        return self::SUCCESS;
    }
}

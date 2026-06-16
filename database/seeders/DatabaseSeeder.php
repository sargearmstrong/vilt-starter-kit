<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $permissions = collect([
            'access admin',
            'manage users',
        ])->mapWithKeys(fn (string $permission): array => [
            $permission => Permission::findOrCreate($permission),
        ]);

        $admin = Role::findOrCreate('admin');
        $admin->syncPermissions($permissions->values());

        $superAdmin = Role::findOrCreate('super-admin');
        $superAdmin->syncPermissions($permissions->values());
    }
}

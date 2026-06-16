<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Inertia\Testing\AssertableInertia;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

beforeEach(function (): void {
    $this->withoutVite();
});

function seedStarterPermissions(): void
{
    app(PermissionRegistrar::class)->forgetCachedPermissions();

    Permission::findOrCreate('access admin');
    Permission::findOrCreate('manage users');

    Role::findOrCreate('admin')->syncPermissions(['access admin', 'manage users']);
    Role::findOrCreate('super-admin')->syncPermissions(['access admin', 'manage users']);
}

it('renders the home page', function (): void {
    $this->get('/')
        ->assertOk()
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Home'));
});

it('redirects guests away from the admin area', function (): void {
    $this->get('/admin')
        ->assertRedirect('/login');
});

it('blocks authenticated users without the admin permission', function (): void {
    $this->actingAs(User::factory()->create())
        ->get('/admin')
        ->assertForbidden();
});

it('allows users with the admin permission into the admin area', function (): void {
    seedStarterPermissions();

    $user = User::factory()->create();
    $user->givePermissionTo('access admin');

    $this->actingAs($user)
        ->get('/admin')
        ->assertOk()
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Admin/Dashboard')
            ->where('auth.user.publicId', $user->public_id)
            ->where('auth.user.name', $user->name)
            ->where('auth.user.email', $user->email)
            ->missing('auth.user.id')
            ->missing('auth.user.created_at')
            ->missing('auth.user.updated_at'));
});

it('logs in admin users through the session auth flow', function (): void {
    seedStarterPermissions();

    $user = User::factory()->create([
        'email' => 'admin@example.test',
        'password' => Hash::make('password'),
    ]);
    $user->assignRole('admin');

    $this->post('/login', [
        'email' => 'admin@example.test',
        'password' => 'password',
    ])->assertRedirect('/admin');

    $this->assertAuthenticatedAs($user);
});

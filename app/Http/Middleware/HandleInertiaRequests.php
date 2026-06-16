<?php

namespace App\Http\Middleware;

use App\Data\SharedUserData;
use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $user = $request->user();

        return [
            ...parent::share($request),
            'app' => [
                'name' => config('app.name'),
                'assetVersion' => $this->version($request),
                'environment' => app()->environment(),
            ],
            'auth' => [
                'user' => fn (): ?SharedUserData => $user ? SharedUserData::fromUser($user) : null,
                'roles' => fn (): array => $user?->getRoleNames()->values()->all() ?? [],
                'permissions' => fn (): array => $user?->getAllPermissions()
                    ->pluck('name')
                    ->values()
                    ->all() ?? [],
            ],
            'flash' => [
                'success' => fn () => $request->session()->get('success'),
                'error' => fn () => $request->session()->get('error'),
            ],
        ];
    }
}

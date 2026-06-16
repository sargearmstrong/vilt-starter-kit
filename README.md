# Laravel Inertia Vue Starter

Reusable starter for custom websites built with Laravel, Vue, Inertia, TypeScript, Tailwind, shadcn-vue-style components, Spatie Data contracts, Docker, MariaDB, optional PostgreSQL/pgvector, Valkey, and a small admin-ready auth layer.

This project is meant to be copied into new project folders before design and business-specific work begins.

## What Is Included

- Laravel 13 on PHP 8.3+
- Vue 3 with the Composition API
- Inertia 3
- TypeScript
- Tailwind 4 via `@tailwindcss/vite`
- shadcn-vue-style local UI components
- Laravel Wayfinder
- Laravel Form Requests for validation
- Spatie Laravel Data for intentional Inertia data contracts
- Inertia shared data for app, auth, roles, permissions, and flash messages
- Session login/logout flow
- Protected `/admin` dashboard shell
- `spatie/laravel-permission` roles and permissions
- `app:create-admin` command for the first admin user
- Pest tests
- Laravel Pint
- Larastan / PHPStan
- ESLint, Prettier, and vue-tsc
- Docker Compose / Sail-style local services
- MariaDB by default
- Valkey available for cache, queues, and sessions
- Optional PostgreSQL with pgvector profile
- Optional Inertia SSR worker profile
- Mailpit for local mail
- Production Inertia error page handling for 403, 404, 500, and 503
- Stale frontend asset reload guard for active browser sessions after deploys

## Intentionally Optional

These are not installed by default so the starter stays clean.

- Pinia: add it only for rich client-only state. Prefer Inertia props, shared data, flash data, local component state, and `useRemember` first.
- Tightenco Ziggy: use it if a project specifically wants the global `route()` helper. This starter defaults to Wayfinder.
- Filament or Backpack: use per project when a full admin panel or CRUD builder is needed.
- Laravel Reverb and Echo: add when real-time features are actually needed. Prefer Reverb over Ratchet for Laravel-native WebSockets.
- ReactPHP: do not add directly unless a project needs low-level async PHP networking. Reverb already uses ReactPHP internally.
- Spatie TypeScript Transformer: add when a project wants generated frontend DTO types from Spatie Data classes.
- Spatie Laravel Query Builder: add for API endpoints that need filtering, sorting, includes, or sparse fieldsets.

## Copy To A New Project

From `/home/bootcamp/projects`:

```bash
rsync -a --exclude=.git --exclude=vendor --exclude=node_modules --exclude=.env vilt-starter-kit/ new-example.com/
cd new-example.com
```

If the starter was copied by another method and includes its Git history, reset the new project's repository timeline before the first commit:

```bash
rm -rf .git
git init -b main
```

Then edit these values:

- `composer.json`: package name and description
- `package.json`: package name
- `.env`: app name, URL, database, mail, and any production-specific values
- `README.md`: project-specific setup notes

Do not copy real secrets into this starter.

## First Local Run

```bash
composer install
cp .env.example .env
php artisan key:generate
./vendor/bin/sail up -d
./vendor/bin/sail npm install
./vendor/bin/sail artisan migrate:fresh --seed
./vendor/bin/sail artisan app:create-admin --email=admin@example.test --password=password --super
./vendor/bin/sail npm run dev
```

Open:

- Site: `http://localhost`
- Mailpit: `http://localhost:8025`

If port 80 is already taken, set `APP_PORT=8080` in `.env` and open `http://localhost:8080`.

If you do not have local PHP or Composer, install dependencies with a temporary Composer container, then use Sail:

```bash
docker run --rm -u "$(id -u):$(id -g)" -v "$PWD:/var/www/html" -w /var/www/html laravelsail/php83-composer:latest composer install
```

## Database Defaults

MariaDB is the default local database:

```env
DB_CONNECTION=mariadb
DB_HOST=mariadb
DB_PORT=3306
DB_DATABASE=starter
DB_USERNAME=sail
DB_PASSWORD=password
```

Default tables:

- `migrations`
- `users`, including internal `id` and public UUIDv7 `public_id`
- `password_reset_tokens`
- `sessions`
- `cache`
- `cache_locks`
- `jobs`
- `job_batches`
- `failed_jobs`
- `roles`
- `permissions`
- `model_has_roles`
- `model_has_permissions`
- `role_has_permissions`

There are no business, CMS, analytics, or visit logging tables by default. Sessions are not analytics. Add project-specific analytics tables later only when needed.

## PostgreSQL And pgvector

PostgreSQL with pgvector is available as an optional Compose profile.

Switch `.env` to:

```env
DB_CONNECTION=pgsql
DB_HOST=pgsql
DB_PORT=5432
DB_DATABASE=starter
DB_USERNAME=sail
DB_PASSWORD=password
FORWARD_PGSQL_PORT=5432
```

Start with:

```bash
./vendor/bin/sail --profile postgres up -d
./vendor/bin/sail artisan migrate:fresh --seed
```

The profile mounts `database/init/10-enable-pgvector.sql`, which runs `CREATE EXTENSION IF NOT EXISTS vector;` when the PostgreSQL volume is first created.

For app migrations that add vector columns, use Laravel's vector support:

```php
Schema::ensureVectorExtensionExists();
$table->vector('embedding', dimensions: 1536);
```

If the PostgreSQL volume already existed before the init SQL was added, enable pgvector manually or recreate that local volume.

## Valkey

Valkey is Redis-protocol compatible. Laravel still calls the driver `redis`.

The starter runs Valkey locally but keeps portable database defaults:

```env
SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database
REDIS_HOST=valkey
```

Opt in per subsystem:

```env
CACHE_STORE=redis
QUEUE_CONNECTION=redis
SESSION_DRIVER=redis
```

Use Valkey for cache first when a site needs faster transient storage. Use it for queues when background jobs become important. Use it for sessions only when the deployment has a stable shared Valkey service.

## Auth And Admin

The starter includes session-based login, logout, and a protected `/admin` route.

Roles:

- `super-admin`
- `admin`

Permissions:

- `access admin`
- `manage users`

Seed roles and permissions:

```bash
./vendor/bin/sail artisan db:seed
```

Create the first admin:

```bash
./vendor/bin/sail artisan app:create-admin --email=admin@example.test --password=password --super
```

The `super-admin` role uses Spatie's recommended `Gate::before` callback and returns `true` for super admins or `null` for normal authorization flow.

## Data Contracts

Do not pass raw Eloquent models directly to Inertia page props or shared props.

The starter uses `app/Data/SharedUserData.php` for `auth.user`. The DTO exposes only:

- `publicId`
- `name`
- `email`

The internal database `id`, timestamps, password fields, remember token, and any future private columns stay server-side unless a project explicitly adds them to a DTO. Keep Laravel Form Requests as the validation default; Spatie Data is used here for output contracts, not as a replacement validation system.

## Inertia Shared Data

Shared props are defined in `app/Http/Middleware/HandleInertiaRequests.php`.

Available on every page:

- `app.name`
- `app.assetVersion`
- `app.environment`
- `auth.user`
- `auth.roles`
- `auth.permissions`
- `flash.success`
- `flash.error`

Keep server data in Inertia props. Use local Vue state for UI-only interactions. Add Pinia only when client-only state becomes complex enough to justify it.

## Asset Deploy Safety

Inertia asset versioning is enabled through `HandleInertiaRequests::version()`. Laravel Vite provides cache-busted asset URLs.

The browser entry also listens for stale dynamic import failures and Vite preload errors. If a user has an old page open during deploy and then navigates to a newly built chunk, the app reloads once to fetch the current assets instead of leaving the user on a broken screen.

## Error Pages

In local and testing environments, Laravel's normal debug/error handling remains active.

In production, 403, 404, 500, and 503 responses render the Inertia `ErrorPage.vue` component so server exceptions match the app's frontend theme.

## Layouts

The starter includes:

- `AppLayout.vue`
- `GuestLayout.vue`
- `AdminLayout.vue`
- `ErrorPage.vue`

Use `AppLayout` for public pages, `GuestLayout` for login-style pages, and `AdminLayout` for protected admin pages.

## shadcn-vue Components

Local UI components live under:

```text
resources/js/components/ui
```

The starter includes a small base set: alert, badge, button, card, input, label, and textarea. Add project-specific shadcn-vue components only as the project needs them.

## Wayfinder

Wayfinder is included because it aligns with the current Laravel starter direction and generates typed frontend route/action helpers.

Generated files are ignored by Git:

```text
resources/js/actions
resources/js/routes
resources/js/wayfinder
```

Regenerate when routes/controllers change:

```bash
./vendor/bin/sail artisan wayfinder:generate
```

Use Ziggy instead only when a project prefers the global `route()` helper and generated route-name types over Wayfinder's action helpers.

## Inertia SSR

SSR is configured but disabled by default:

```env
INERTIA_SSR_ENABLED=false
INERTIA_SSR_URL=http://inertia.ssr:13714
```

Build SSR assets:

```bash
./vendor/bin/sail npm run build:ssr
```

Run the optional SSR worker profile:

```bash
./vendor/bin/sail --profile ssr up -d
```

Then set:

```env
INERTIA_SSR_ENABLED=true
```

Production SSR needs a process monitor for `php artisan inertia:start-ssr`, just like queue workers or Reverb.

## Reverb And Echo

Broadcasting defaults to `log`.

When a project needs WebSockets:

1. Install Laravel Reverb.
2. Install Laravel Echo and Pusher JS on the frontend.
3. Set `BROADCAST_CONNECTION=reverb`.
4. Add a process monitor for Reverb in production.

Prefer Reverb over Ratchet for Laravel apps unless the project has a specific low-level WebSocket reason to choose Ratchet.

## Primary Keys

Default to Laravel's auto-incrementing unsigned big integer IDs for internal primary keys.

Use UUIDv7 `public_id` columns for records that need to be referenced by Vue, forms, URLs, APIs, or admin screens. UUIDv7 is time-sortable, supported by Laravel through `Str::uuid7()`, and maps cleanly to PostgreSQL's native `uuid` type while remaining compatible with MariaDB through Laravel's UUID column support.

Consider ULIDs for project-specific records when:

- URLs should not expose sequential IDs.
- IDs need to be generated before a database write.
- Multiple systems may create records independently.
- A shorter, URL-friendly sortable identifier is preferred over UUID syntax.

For this starter, the default pattern is: bigint `id` internally, UUIDv7 `public_id` externally.

## Quality Commands

PHP:

```bash
composer lint
composer lint:check
composer types:check
composer test
composer ci:check
composer dev:clear
```

Frontend:

```bash
npm run lint
npm run lint:check
npm run format
npm run format:check
npm run types:check
npm run build
npm run build:ssr
```

Inside Sail:

```bash
./vendor/bin/sail composer ci:check
./vendor/bin/sail npm run build
```

`composer dev:clear` runs Laravel cache clearing plus the Spatie permission cache reset.

## Deployment Notes

Typical production deploy sequence:

```bash
composer install --no-dev --prefer-dist --optimize-autoloader
npm ci
npm run build
php artisan migrate --force --isolated
php artisan optimize
php artisan reload
```

Why `migrate --force --isolated`:

- `--force` prevents production pipelines from hanging on Laravel's interactive confirmation prompt.
- `--isolated` prevents multiple deploy workers from attempting migrations at the same time.

Use `php artisan reload` when long-running services need the new code, such as queue workers, Reverb, Octane, or the Inertia SSR worker.

When pairing PostgreSQL or pgvector workloads with Valkey-backed cache, queues, or sessions, monitor database and Valkey connection counts, worker concurrency, timeouts, and any pooler settings. High-concurrency deploys can fail from connection pressure before they fail from application code.

For cPanel/shared hosting, build assets locally or in CI, upload `public/build`, run Composer on the server if available, and ensure `storage` and `bootstrap/cache` are writable.

## Troubleshooting

- Docker port conflict: change `APP_PORT`, `FORWARD_DB_PORT`, `FORWARD_PGSQL_PORT`, `FORWARD_REDIS_PORT`, or Mailpit ports in `.env`.
- Database connection fails: confirm `DB_HOST` matches the active service, usually `mariadb` or `pgsql`.
- PostgreSQL pgvector missing: recreate the local PostgreSQL volume or run `CREATE EXTENSION IF NOT EXISTS vector;` manually.
- Vite hot reload fails: confirm `VITE_PORT` is open and `./vendor/bin/sail npm run dev` is running.
- Admin access returns 403: run `db:seed`, then assign `admin` or `super-admin` to the user.
- Permission changes seem stale: run `composer dev:clear`.
- Production env changes are ignored: run `php artisan optimize:clear`, then `php artisan optimize`.
- Active browser session breaks after deploy: reload once; the starter also has a stale asset guard for dynamic import/preload failures.

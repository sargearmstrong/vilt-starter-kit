<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Permission\PermissionRegistrar;
use Throwable;

class DevClearCommand extends Command
{
    protected $signature = 'app:dev-clear';

    protected $description = 'Clear local framework caches and Spatie permission cache safely.';

    public function handle(): int
    {
        $usingStarterCacheFallback = $this->useArrayCacheWhenStarterCacheIsUnavailable();

        try {
            $this->call('optimize:clear');
        } catch (Throwable $exception) {
            $this->warn('optimize:clear could not clear every cache backend.');
            $this->line($exception->getMessage());

            foreach (['config:clear', 'route:clear', 'view:clear', 'event:clear', 'clear-compiled'] as $command) {
                $this->call($command);
            }
        }

        $usingStarterCacheFallback = $this->useArrayCacheWhenStarterCacheIsUnavailable()
            || $usingStarterCacheFallback;

        if ($usingStarterCacheFallback) {
            $this->info('Spatie permission cache reset skipped until a real cache store is configured.');

            return self::SUCCESS;
        }

        try {
            app(PermissionRegistrar::class)->forgetCachedPermissions();
            $this->info('Spatie permission cache cleared.');
        } catch (Throwable $exception) {
            $this->warn('Spatie permission cache could not be cleared.');
            $this->line($exception->getMessage());
        }

        return self::SUCCESS;
    }

    protected function useArrayCacheWhenStarterCacheIsUnavailable(): bool
    {
        if (! $this->starterSqliteCacheIsUnavailable()) {
            return false;
        }

        config(['cache.default' => 'array']);

        return true;
    }

    protected function starterSqliteCacheIsUnavailable(): bool
    {
        return config('cache.default') === 'database'
            && config('database.default') === 'sqlite'
            && ! file_exists(database_path('database.sqlite'));
    }
}

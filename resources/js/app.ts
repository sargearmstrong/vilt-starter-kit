import { createInertiaApp, router } from '@inertiajs/vue3';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';
const staleAssetReloadKey = 'starter:stale-asset-reload';

function isStaleAssetError(reason: unknown): boolean {
    const message = reason instanceof Error ? reason.message : String(reason);

    return [
        'Failed to fetch dynamically imported module',
        'Importing a module script failed',
        'error loading dynamically imported module',
        'Unable to preload CSS',
    ].some((fragment) => message.includes(fragment));
}

function reloadForFreshAssets(): void {
    if (sessionStorage.getItem(staleAssetReloadKey) === '1') {
        return;
    }

    sessionStorage.setItem(staleAssetReloadKey, '1');
    window.location.reload();
}

if (typeof window !== 'undefined') {
    window.addEventListener('vite:preloadError', (event) => {
        event.preventDefault();
        reloadForFreshAssets();
    });

    window.addEventListener('unhandledrejection', (event) => {
        if (!isStaleAssetError(event.reason)) {
            return;
        }

        event.preventDefault();
        reloadForFreshAssets();
    });

    router.on('navigate', () => {
        sessionStorage.removeItem(staleAssetReloadKey);
    });
}

createInertiaApp({
    title: (title) => (title ? `${title} - ${appName}` : appName),
    progress: {
        color: '#2563eb',
    },
});

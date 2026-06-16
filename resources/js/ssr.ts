import { createInertiaApp } from '@inertiajs/vue3';
import { renderToString } from '@vue/server-renderer';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

export default createInertiaApp({
    title: (title) => (title ? `${title} - ${appName}` : appName),
    render: renderToString,
});

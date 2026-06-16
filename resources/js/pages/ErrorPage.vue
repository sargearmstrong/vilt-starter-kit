<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps<{
    status: 403 | 404 | 500 | 503;
}>();

const title = computed(() => {
    return {
        403: 'Forbidden',
        404: 'Page not found',
        500: 'Server error',
        503: 'Service unavailable',
    }[props.status];
});

const description = computed(() => {
    return {
        403: 'You do not have permission to access this page.',
        404: 'The page you are looking for could not be found.',
        500: 'Something went wrong on the server.',
        503: 'The application is temporarily unavailable.',
    }[props.status];
});
</script>

<template>
    <Head :title="`${status}: ${title}`" />

    <main
        class="grid min-h-screen place-items-center bg-slate-950 px-4 py-12 text-white"
    >
        <section class="w-full max-w-lg text-center">
            <p class="text-sm font-semibold text-blue-300">{{ status }}</p>
            <h1 class="mt-3 text-4xl font-semibold tracking-normal">
                {{ title }}
            </h1>
            <p class="mt-4 text-base leading-7 text-slate-300">
                {{ description }}
            </p>
            <Link
                href="/"
                class="mt-8 inline-flex h-11 items-center rounded-md bg-white px-4 text-sm font-semibold text-slate-950 hover:bg-slate-100"
            >
                Back home
            </Link>
        </section>
    </main>
</template>

<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';

import type { SharedPageProps } from '@/types';

const page = usePage<SharedPageProps>();
</script>

<template>
    <div
        class="flex min-h-screen items-center justify-center bg-slate-50 px-4 py-10 text-slate-950"
    >
        <div class="w-full max-w-md">
            <Link
                href="/"
                class="mx-auto mb-8 flex w-fit items-center gap-3 rounded-md font-semibold text-slate-950 focus-visible:ring-2 focus-visible:ring-blue-600 focus-visible:ring-offset-2 focus-visible:outline-none"
            >
                <span
                    class="grid h-9 w-9 place-items-center rounded-md bg-slate-950 text-sm font-bold text-white"
                >
                    L
                </span>
                <span>{{ page.props.app.name }}</span>
            </Link>

            <slot />
        </div>
    </div>
</template>

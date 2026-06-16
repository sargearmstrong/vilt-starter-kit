<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { LayoutDashboard, LogIn, LogOut } from '@lucide/vue';
import { computed } from 'vue';

import type { SharedPageProps } from '@/types';

const page = usePage<SharedPageProps>();
const user = computed(() => page.props.auth.user);
const canAccessAdmin = computed(() =>
    page.props.auth.permissions.includes('access admin'),
);
</script>

<template>
    <div class="min-h-screen bg-slate-50 text-slate-950">
        <header class="border-b border-slate-200 bg-white">
            <div
                class="mx-auto flex max-w-6xl items-center justify-between px-4 py-4 sm:px-6 lg:px-8"
            >
                <Link
                    href="/"
                    class="flex items-center gap-3 rounded-md font-semibold text-slate-950 focus-visible:ring-2 focus-visible:ring-blue-600 focus-visible:ring-offset-2 focus-visible:outline-none"
                >
                    <span
                        class="grid h-9 w-9 place-items-center rounded-md bg-slate-950 text-sm font-bold text-white"
                    >
                        L
                    </span>
                    <span>{{ page.props.app.name }}</span>
                </Link>

                <nav class="flex items-center gap-2" aria-label="Primary">
                    <Link
                        v-if="canAccessAdmin"
                        href="/admin"
                        class="inline-flex h-10 items-center gap-2 rounded-md px-3 text-sm font-medium text-slate-700 hover:bg-slate-100 hover:text-slate-950"
                    >
                        <LayoutDashboard class="h-4 w-4" />
                        Admin
                    </Link>

                    <Link
                        v-if="!user"
                        href="/login"
                        class="inline-flex h-10 items-center gap-2 rounded-md px-3 text-sm font-medium text-slate-700 hover:bg-slate-100 hover:text-slate-950"
                    >
                        <LogIn class="h-4 w-4" />
                        Sign In
                    </Link>

                    <Link
                        v-else
                        href="/logout"
                        method="post"
                        as="button"
                        class="inline-flex h-10 items-center gap-2 rounded-md px-3 text-sm font-medium text-slate-700 hover:bg-slate-100 hover:text-slate-950"
                    >
                        <LogOut class="h-4 w-4" />
                        Sign Out
                    </Link>
                </nav>
            </div>
        </header>

        <main class="mx-auto max-w-6xl px-4 py-10 sm:px-6 lg:px-8">
            <slot />
        </main>
    </div>
</template>

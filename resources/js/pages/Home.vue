<script setup lang="ts">
import { Head, Link, usePage } from '@inertiajs/vue3';
import { ArrowRight, CheckCircle2, ShieldCheck, Sparkles } from '@lucide/vue';
import { computed } from 'vue';

import Badge from '@/components/ui/badge/Badge.vue';
import Card from '@/components/ui/card/Card.vue';
import CardContent from '@/components/ui/card/CardContent.vue';
import CardHeader from '@/components/ui/card/CardHeader.vue';
import CardTitle from '@/components/ui/card/CardTitle.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import type { SharedPageProps } from '@/types';

const page = usePage<SharedPageProps>();
const user = computed(() => page.props.auth.user);

const included = [
    'Laravel 13',
    'Vue 3',
    'Inertia 3',
    'TypeScript',
    'Tailwind 4',
    'shadcn-vue',
    'Wayfinder',
    'Spatie Permission',
    'MariaDB',
    'Valkey',
];
</script>

<template>
    <Head title="Home">
        <meta
            name="description"
            content="Reusable Laravel, Vue, Inertia, TypeScript, Tailwind, and shadcn-vue starter."
        />
    </Head>

    <AppLayout>
        <section class="grid gap-8 lg:grid-cols-[1fr_360px] lg:items-start">
            <div>
                <Badge>Reusable starter</Badge>
                <h1
                    class="mt-5 max-w-3xl text-4xl font-semibold tracking-normal text-slate-950 sm:text-5xl"
                >
                    Build the custom site, keep the stack predictable.
                </h1>
                <p class="mt-5 max-w-2xl text-lg leading-8 text-slate-600">
                    This project is intentionally lean: a typed Inertia app shell,
                    admin-ready permissions, Docker services, and enough structure
                    for the next website without carrying project-specific content.
                </p>

                <div class="mt-8 flex flex-wrap gap-3">
                    <Link
                        v-if="user"
                        href="/admin"
                        class="inline-flex h-11 items-center gap-2 rounded-md bg-blue-600 px-4 text-sm font-semibold text-white shadow-sm hover:bg-blue-700"
                    >
                        Open admin
                        <ArrowRight class="h-4 w-4" />
                    </Link>
                    <Link
                        v-else
                        href="/login"
                        class="inline-flex h-11 items-center gap-2 rounded-md bg-blue-600 px-4 text-sm font-semibold text-white shadow-sm hover:bg-blue-700"
                    >
                        Sign in
                        <ArrowRight class="h-4 w-4" />
                    </Link>
                    <a
                        href="https://laravel.com/docs"
                        class="inline-flex h-11 items-center rounded-md border border-slate-300 bg-white px-4 text-sm font-semibold text-slate-950 hover:bg-slate-50"
                    >
                        Laravel docs
                    </a>
                </div>
            </div>

            <Card>
                <CardHeader>
                    <CardTitle>Base Stack</CardTitle>
                </CardHeader>
                <CardContent>
                    <ul class="grid gap-3 text-sm text-slate-700">
                        <li
                            v-for="item in included"
                            :key="item"
                            class="flex items-center gap-2"
                        >
                            <CheckCircle2 class="h-4 w-4 text-blue-600" />
                            {{ item }}
                        </li>
                    </ul>
                </CardContent>
            </Card>
        </section>

        <section class="mt-12 grid gap-4 md:grid-cols-2">
            <Card>
                <CardHeader>
                    <CardTitle class="flex items-center gap-2">
                        <ShieldCheck class="h-5 w-5 text-blue-600" />
                        Admin-ready
                    </CardTitle>
                </CardHeader>
                <CardContent class="text-sm leading-6 text-slate-600">
                    Users, sessions, roles, permissions, and a protected admin route
                    are ready for project-specific CMS or operational screens.
                </CardContent>
            </Card>

            <Card>
                <CardHeader>
                    <CardTitle class="flex items-center gap-2">
                        <Sparkles class="h-5 w-5 text-blue-600" />
                        Copy-friendly
                    </CardTitle>
                </CardHeader>
                <CardContent class="text-sm leading-6 text-slate-600">
                    MariaDB is the default database, PostgreSQL with pgvector is one
                    profile away, and Valkey can be enabled per cache, queue, or
                    session need.
                </CardContent>
            </Card>
        </section>
    </AppLayout>
</template>

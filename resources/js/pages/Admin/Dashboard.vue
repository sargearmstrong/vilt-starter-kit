<script setup lang="ts">
import { Head, usePage } from '@inertiajs/vue3';
import { KeyRound, ShieldCheck, UserCircle } from '@lucide/vue';

import Card from '@/components/ui/card/Card.vue';
import CardContent from '@/components/ui/card/CardContent.vue';
import CardHeader from '@/components/ui/card/CardHeader.vue';
import CardTitle from '@/components/ui/card/CardTitle.vue';
import AdminLayout from '@/layouts/AdminLayout.vue';
import type { SharedPageProps } from '@/types';

const page = usePage<SharedPageProps>();
</script>

<template>
    <Head title="Admin" />

    <AdminLayout>
        <div class="grid gap-4 lg:grid-cols-3">
            <Card>
                <CardHeader>
                    <CardTitle class="flex items-center gap-2">
                        <UserCircle class="h-5 w-5 text-blue-600" />
                        User
                    </CardTitle>
                </CardHeader>
                <CardContent class="text-sm leading-6 text-slate-600">
                    {{ page.props.auth.user?.name }}
                    <br />
                    {{ page.props.auth.user?.email }}
                </CardContent>
            </Card>

            <Card>
                <CardHeader>
                    <CardTitle class="flex items-center gap-2">
                        <ShieldCheck class="h-5 w-5 text-blue-600" />
                        Roles
                    </CardTitle>
                </CardHeader>
                <CardContent class="text-sm leading-6 text-slate-600">
                    {{ page.props.auth.roles.join(', ') || 'No roles assigned' }}
                </CardContent>
            </Card>

            <Card>
                <CardHeader>
                    <CardTitle class="flex items-center gap-2">
                        <KeyRound class="h-5 w-5 text-blue-600" />
                        Permissions
                    </CardTitle>
                </CardHeader>
                <CardContent class="text-sm leading-6 text-slate-600">
                    {{
                        page.props.auth.permissions.join(', ') ||
                        'No permissions assigned'
                    }}
                </CardContent>
            </Card>
        </div>
    </AdminLayout>
</template>

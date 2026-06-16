<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
</script>

<template>
    <AppLayout>
        <div class="space-y-6">
            <div>
                <p class="text-sm font-medium text-blue-700">Admin</p>
                <h1 class="mt-2 text-3xl font-semibold tracking-normal text-slate-950">
                    Dashboard
                </h1>
            </div>

            <slot />
        </div>
    </AppLayout>
</template>

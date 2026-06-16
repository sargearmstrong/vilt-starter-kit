<script setup lang="ts">
import { cn } from '@/lib/utils';

const props = defineProps<{ class?: string }>();
</script>

<template>
    <div
        :class="
            cn(
                'rounded-md border border-blue-200 bg-blue-50 px-4 py-3 text-sm font-medium text-blue-900',
                props.class,
            )
        "
    >
        <slot />
    </div>
</template>

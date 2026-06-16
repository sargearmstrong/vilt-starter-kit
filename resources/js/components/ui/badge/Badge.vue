<script setup lang="ts">
import { cn } from '@/lib/utils';

const props = withDefaults(
    defineProps<{
        class?: string;
        variant?: 'default' | 'secondary' | 'outline';
    }>(),
    {
        variant: 'default',
    },
);

const variants = {
    default: 'bg-slate-950 text-white',
    secondary: 'bg-blue-100 text-blue-950',
    outline: 'border border-slate-300 bg-white text-slate-700',
};
</script>

<template>
    <span
        :class="
            cn(
                'inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold',
                variants[variant],
                props.class,
            )
        "
    >
        <slot />
    </span>
</template>

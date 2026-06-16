<script setup lang="ts">
import { cn } from '@/lib/utils';

const props = withDefaults(
    defineProps<{
        class?: string;
        size?: 'sm' | 'md' | 'lg' | 'icon';
        type?: 'button' | 'submit' | 'reset';
        variant?: 'default' | 'secondary' | 'outline' | 'ghost' | 'link';
    }>(),
    {
        size: 'md',
        type: 'button',
        variant: 'default',
    },
);

const variants = {
    default: 'bg-blue-600 text-white shadow-sm hover:bg-blue-700',
    secondary: 'bg-slate-100 text-slate-950 hover:bg-slate-200',
    outline: 'border border-slate-300 bg-white text-slate-950 hover:bg-slate-50',
    ghost: 'text-slate-800 hover:bg-slate-100',
    link: 'h-auto p-0 text-blue-700 underline-offset-4 hover:underline',
};

const sizes = {
    sm: 'h-9 px-3 text-sm',
    md: 'h-11 px-4 text-sm',
    lg: 'h-12 px-5 text-base',
    icon: 'h-10 w-10 p-0',
};
</script>

<template>
    <button
        :type="type"
        :class="
            cn(
                'inline-flex shrink-0 items-center justify-center gap-2 rounded-md font-semibold transition-colors focus-visible:ring-2 focus-visible:ring-blue-600 focus-visible:ring-offset-2 focus-visible:outline-none disabled:pointer-events-none disabled:opacity-50',
                variants[variant],
                sizes[size],
                props.class,
            )
        "
    >
        <slot />
    </button>
</template>

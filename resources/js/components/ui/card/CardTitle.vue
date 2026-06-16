<script setup lang="ts">
import { cn } from '@/lib/utils';

const props = defineProps<{ class?: string }>();
</script>

<template>
    <h3 :class="cn('text-lg leading-tight font-semibold text-slate-950', props.class)">
        <slot />
    </h3>
</template>

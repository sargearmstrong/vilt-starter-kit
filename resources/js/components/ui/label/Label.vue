<script setup lang="ts">
import { cn } from '@/lib/utils';

const props = defineProps<{ class?: string }>();
</script>

<template>
    <label :class="cn('text-sm font-semibold text-slate-900', props.class)">
        <slot />
    </label>
</template>

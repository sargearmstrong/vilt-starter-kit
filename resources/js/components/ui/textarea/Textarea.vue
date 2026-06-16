<script setup lang="ts">
import { cn } from '@/lib/utils';

defineOptions({ inheritAttrs: false });

const props = defineProps<{ class?: string }>();
const model = defineModel<string | null>();
</script>

<template>
    <textarea
        v-bind="$attrs"
        v-model="model"
        :class="
            cn(
                'min-h-28 w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-950 shadow-xs transition-colors placeholder:text-slate-400 focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20 focus:outline-none disabled:cursor-not-allowed disabled:opacity-50',
                props.class,
            )
        "
    />
</template>

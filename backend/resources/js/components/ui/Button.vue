<script lang="ts" setup>
import { computed } from 'vue';

const props = withDefaults(defineProps<{
    label: string;
    url?: string;
    type?: 'button' | 'submit' | 'reset';
    style?: 'primary' | 'secondary' | 'danger' | 'warning' | 'info' | 'outline';
    size?: 'xs' | 'sm' | 'md' | 'lg' | 'xl';
    icon?: string;
    class?: string;
}>(), {
    type: 'button',
    style: 'primary',
    class: '',
    size: 'sm',
});

const styles: Record<string, string> = {
    primary: 'bg-emerald-500 border border-emerald-600 text-white',
    secondary: 'bg-gray-500 border border-gray-600 text-white',
    danger: 'bg-red-500 border border-red-600 text-white',
    warning: 'bg-yellow-500 border border-yellow-600 text-white',
    info: 'bg-blue-500 border border-blue-600 text-white',
    outline: 'bg-white border border-gray-600 text-gray-600',
};

const sizeStyles: Record<string, string> = {
    xs: 'p-1 text-xs',
    sm: 'px-2 py-1 text-sm',
    md: 'px-2 py-1 text-md',
    lg: 'px-3 py-2 text-lg',
    xl: 'px-4 py-3 text-xl',
};

const baseStyles = 'rounded-md hover:brightness-90 flex items-center gap-1 w-fit cursor-pointer';

const computedClass = computed(() => {
    return [
        styles[props.style as keyof typeof styles],
        sizeStyles[props.size as keyof typeof sizeStyles],
        baseStyles,
        props.class,
    ].join(' ');
});
</script>

<template>
    <a :href="url" v-if="url" :class="computedClass" :title="label">
        <i :class="icon" v-if="icon"></i>
        <span>{{ label }}</span>
    </a>
    <button v-else :type="type" :class="computedClass" :title="label">
        <i :class="icon" v-if="icon"></i>
        <span>{{ label }}</span>
    </button>
</template>
<script lang="ts" setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = withDefaults(defineProps<{
    method?: 'get' | 'post' | 'put' | 'delete';
    element?: 'button' | 'a';
    label?: string;
    theme?: 'primary' | 'secondary' | 'danger' | 'warning' | 'info' | 'outline' | 'transparent';
    size?: 'xs' | 'sm' | 'md' | 'lg' | 'xl';
    icon?: string;
    processing?: boolean;
    disabled?: boolean;
    href?: string;
    type?: 'button' | 'submit' | 'reset';
}>(), {
    element: 'button',
    theme: 'primary',
    size: 'sm',
    processing: false,
    disabled: false,
    href: undefined,
    type: 'button',
});

const themes: Record<string, string> = {
    primary: 'bg-emerald-500 border border-emerald-600 text-theme',
    secondary: 'bg-theme-dark0 border border-sky-600 text-white',
    danger: 'bg-red-500 border border-red-600 text-theme',
    warning: 'bg-yellow-500 border border-yellow-600 text-theme',
    info: 'bg-blue-500 border border-blue-600 text-theme',
    outline: 'bg-white border border-gray-600 text-gray-600',
    transparent: 'bg-transparent border-none text-gray-600',
};

const sizeStyles: Record<string, string> = {
    xs: 'p-1 text-xs',
    sm: 'px-2 py-1 text-sm',
    md: 'px-2 py-1 text-md',
    lg: 'px-3 py-2 text-lg',
    xl: 'px-4 py-3 text-xl',
};

const baseStyles = 'rounded-md hover:brightness-90 flex items-center gap-1 w-fit h-fit cursor-pointer shrink-0';
const disabledStyles = 'opacity-60 pointer-events-none';

const computedClass = computed(() => {
    return [
        themes[props.theme as keyof typeof themes],
        sizeStyles[props.size as keyof typeof sizeStyles],
        baseStyles,
        props.disabled && disabledStyles,
    ].join(' ');
});

const handleClick = (event: MouseEvent) => {
    if (props.disabled) {
        event.preventDefault();
        event.stopPropagation();
        return;
    };
};
</script>

<template>
    <Link v-if="href" :href="href" :method="method" :class="computedClass" :title="label" :disabled="disabled" :aria-disabled="disabled"
        @click="handleClick">
        <i :class="icon" v-if="icon && !processing"></i>
        <i class="fa-regular fa-loader fa-spin" v-if="processing"></i>
        <span>{{ label }}</span>
    </Link>
    <button v-else :type="type" :class="computedClass" :title="label" :disabled="disabled" :aria-disabled="disabled"
        @click="handleClick">
        <i :class="icon" v-if="icon && !processing"></i>
        <i class="fa-regular fa-loader fa-spin" v-if="processing"></i>
        <span>{{ label }}</span>
    </button>
</template>
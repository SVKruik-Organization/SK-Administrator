<script lang="ts" setup>
import { Link } from '@inertiajs/vue3';
import { computed, HTMLAttributes, ButtonHTMLAttributes } from 'vue';

const props = withDefaults(defineProps<{
    label: string;
    url?: string;
    method?: 'get' | 'post' | 'put' | 'delete';
    type?: ButtonHTMLAttributes['type'];
    style?: 'primary' | 'secondary' | 'danger' | 'warning' | 'info' | 'outline';
    size?: 'xs' | 'sm' | 'md' | 'lg' | 'xl';
    icon?: string;
    class?: HTMLAttributes['class'];
    disabled?: HTMLButtonElement['disabled'];
    processing?: boolean;
}>(), {
    type: 'button',
    style: 'primary',
    class: '',
    size: 'sm',
    disabled: false,
    processing: false,
});

const styles: Record<string, string> = {
    primary: 'bg-emerald-500 border border-emerald-600 text-white',
    secondary: 'bg-sky-500 border border-sky-600 text-white',
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

const baseStyles = 'rounded-md hover:brightness-90 flex items-center gap-1 w-fit h-fit cursor-pointer shrink-0';
const disabledStyles = 'opacity-60 pointer-events-none';

const computedClass = computed(() => {
    return [
        styles[props.style as keyof typeof styles],
        sizeStyles[props.size as keyof typeof sizeStyles],
        baseStyles,
        props.disabled && disabledStyles,
        props.class,
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
    <Link :href="url" :method="method" v-if="url" :class="computedClass" :title="label" :disabled="disabled" :aria-disabled="disabled"
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
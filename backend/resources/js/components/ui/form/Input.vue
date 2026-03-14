<script lang="ts" setup>
import { HTMLAttributes, InputHTMLAttributes } from 'vue';
import { useVModel } from "@vueuse/core"

const props = defineProps<{
    label?: string;
    name: string;
    defaultValue?: string | number;
    modelValue?: string | number | null;
    placeholder?: InputHTMLAttributes['placeholder'];
    type?: InputHTMLAttributes['type'];
    class?: HTMLAttributes['class'];
}>();

const emits = defineEmits<{
    (e: "update:modelValue", payload: string | number): void
}>();

const modelValue = useVModel(props, "modelValue", emits, {
    passive: true,
    defaultValue: props.defaultValue,
});
</script>

<template>
    <div class="flex flex-col gap-2">
        <label :for="name" v-if="label">{{ label }}</label>
        <input :type="type" :name="name" :id="name" v-model="modelValue" :placeholder="placeholder" :class="class" class="w-full p-2 border border-gray-300 bg-white rounded-md" />
    </div>
</template>
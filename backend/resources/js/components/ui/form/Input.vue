<script lang="ts" setup>
import { computed, useAttrs } from 'vue';
import { useVModel } from "@vueuse/core"

defineOptions({
    inheritAttrs: false,
});

const props = defineProps<{
    label?: string;
    defaultValue?: string | number;
    modelValue?: string | number | null;
}>();

const attrs = useAttrs();
const id = computed(() => attrs.id as string);

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
        <label :for="id" v-if="label">{{ label }}</label>
        <input v-model="modelValue" :name="id" class="w-full rounded-md border-2 border-theme-dark p-2"
            v-bind="$attrs" />
    </div>
</template>
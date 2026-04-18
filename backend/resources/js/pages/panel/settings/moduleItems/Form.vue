<script lang="ts" setup>
import { Form } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import PanelLayout from '@/layouts/PanelLayout.vue';
import type { Module, ModuleItem } from '@/types/custom';
import { update, store, destroy } from '@/routes/panel/settings/module-items'
import Button from '@/components/ui/form/Button.vue';
import Input from '@/components/ui/form/Input.vue';
import InputError from '@/components/ui/form/InputError.vue';
import TabBar from '@/components/ui/TabBar.vue';
import TabItem from '@/components/ui/TabItem.vue';

defineOptions({
    layout: PanelLayout,
});

// Props
const props = defineProps<{
    moduleItem?: ModuleItem;
    module: Module;
}>();

// Reactive data
const name = ref<Record<string, string>>(props.moduleItem
    ? JSON.parse(JSON.stringify(props.moduleItem.name))
    : { en: '', nl: '' });
const formUrl = computed(() => {
    return props.moduleItem
        ? update.url({ module_item: props.moduleItem.id, module: props.module.id })
        : store.url({ module: props.module.id });
});

// Computed properties
const hasChanges = computed(() => {
    if (!props.moduleItem) return true;
    return JSON.stringify(name.value) !== JSON.stringify(props.moduleItem.name);
});

// Buttons
const moduleItemSaveButton = {
    label: 'Wijzigingen opslaan',
    icon: 'fa-regular fa-save',
};

const moduleItemDestroyButton = {
    label: 'Item verwijderen',
    icon: 'fa-regular fa-trash',
    url: props.moduleItem ? destroy.url({ module_item: props.moduleItem.id, module: props.module.id }) : null,
};
</script>

<template>
    <div class="flex flex-col gap-4">
        <p class="text-gray-700">
            Item van de {{ module.name?.['en'] }} module bewerken. Als het item toegevoegd is aan het profiel is het
            zichtbaar in de sidebar.
        </p>
        <TabBar>
            <TabItem name="data" label="Gegevens">
                <Form :action="formUrl" :method="moduleItem ? 'put' : 'post'"
                    v-slot="{ errors, processing, recentlySuccessful, clearErrors }"
                    class="grid grid-cols-3 gap-2 gap-y-4 p-4 rounded-lg border-2 border-theme-dark">
                    <div class="flex flex-col gap-2 col-span-3">
                        <Input type="text" id="name[en]" v-model="name['en']" @input="clearErrors('name.en')"
                            label="Engelse naam" />
                        <InputError :message="errors['name.en']" />
                    </div>
                    <div class="flex flex-col gap-2 col-span-3">
                        <Input type="text" id="name[nl]" v-model="name['nl']" @input="clearErrors('name.nl')"
                            label="Nederlandse naam" />
                        <InputError :message="errors['name.nl']" />
                    </div>
                    <div class="col-span-4 flex flex-col gap-2 mt-4 items-end">
                        <Button :label="moduleItemSaveButton.label" :icon="moduleItemSaveButton.icon" type="submit"
                            :disabled="!hasChanges || processing" :processing="processing" />
                        <Transition enter-active-class="transition ease-in-out" enter-from-class="opacity-0"
                            leave-active-class="transition ease-in-out" leave-to-class="opacity-0">
                            <p v-show="recentlySuccessful" class="text-sm text-emerald-500">
                                Wijzigingen opgeslagen.
                            </p>
                        </Transition>
                    </div>
                </Form>
            </TabItem>
            <TabItem name="management" label="Beheer">
                <Button :label="moduleItemDestroyButton.label" :icon="moduleItemDestroyButton.icon"
                    theme="danger" :href="moduleItemDestroyButton.url ?? ''" method="delete" />
            </TabItem>
        </TabBar>
    </div>
</template>
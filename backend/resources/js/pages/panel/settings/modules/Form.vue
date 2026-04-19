<script lang="ts" setup>
import { Form } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import PanelLayout from '@/layouts/PanelLayout.vue';
import type { Module, OrderedTableData } from '@/types/custom';
import { update, destroy, store } from '@/routes/panel/settings/modules'
import { create } from '@/routes/panel/settings/module-items'
import Button from '@/components/ui/form/Button.vue';
import TabBar from '@/components/ui/TabBar.vue';
import TabItem from '@/components/ui/TabItem.vue';
import Input from '@/components/ui/form/Input.vue';
import OrderedTable from '@/components/ui/OrderedTable.vue';
import InputError from '@/components/ui/form/InputError.vue';
import { reorder } from '@/routes/panel/settings/module-items';

defineOptions({
    layout: PanelLayout,
});

// Props
const props = defineProps<{
    module?: Module;
    table?: OrderedTableData;
}>();

// Reactive data
const name = ref<Record<string, string>>(props.module ? JSON.parse(JSON.stringify(props.module.name)) : { en: '', nl: '' });
const icon = ref<string | null>(props.module ? props.module.icon : null);
const formUrl = computed(() => {
    return props.module
        ? update.url({ module: props.module.id })
        : store.url();
});

// Computed properties
const hasChanges = computed(() => {
    if (!props.module) return true;
    return JSON.stringify(name.value) !== JSON.stringify(props.module.name) || icon.value !== props.module.icon;
});

// Buttons
const moduleSaveButton = {
    label: 'Wijzigingen opslaan',
    icon: 'fa-regular fa-save',
};

const destroyButton = computed(() => ({
    label: 'Module verwijderen',
    icon: 'fa-regular fa-trash',
    url: props.module ? destroy.url({ module: props.module.id }) : null,
}));

const moduleItemCreateButton = computed(() => ({
    label: 'Item toevoegen',
    icon: 'fa-regular fa-plus',
    url: props.module ? create.url({ module: props.module.id }) : null,
}));

const reorderUrl = computed(() =>
    props.module ? reorder.url({ module: props.module.id }) : '',
);
</script>

<template>
    <div class="flex flex-col gap-4">
        <TabBar>
            <TabItem name="data" label="Gegevens">
                <Form :action="formUrl" :method="module ? 'put' : 'post'"
                    v-slot="{ errors, processing, recentlySuccessful, clearErrors }"
                    class="grid grid-cols-4 gap-2 gap-y-4 p-4 rounded-lg border-2 border-gray-100">
                    <div class="flex flex-col gap-2 col-span-2">
                        <Input type="text" id="name[en]" v-model="name['en']" @input="clearErrors('name.en')"
                            label="Engelse naam" />
                        <InputError :message="errors['name.en']" />
                    </div>
                    <div class="flex flex-col gap-2 col-span-2">
                        <Input type="text" id="name[nl]" v-model="name['nl']" @input="clearErrors('name.nl')"
                            label="Nederlandse naam" />
                        <InputError :message="errors['name.nl']" />
                    </div>
                    <div class="flex flex-col gap-2 col-span-4">
                        <label for="icon">Pictogram</label>
                        <div class="flex items-center gap-2">
                            <i :class="`fa-regular ${icon}`" class="text-gray-500 text-2xl"></i>
                            <div class="flex flex-col gap-2 flex-1">
                                <Input type="text" id="icon" v-model="icon" @input="clearErrors('icon')"
                                    placeholder="fa-icon" />
                            </div>
                        </div>
                        <p v-show="errors.icon" class="text-red-500 ml-10">{{ errors.icon }}</p>
                    </div>
                    <div class="col-span-4 flex flex-col gap-2 mt-4 items-end">
                        <Button :label="moduleSaveButton.label" :icon="moduleSaveButton.icon" type="submit"
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
            <TabItem name="items" label="Items" v-if="module">
                <div class="flex flex-col gap-8">
                    <div class="flex justify-between items-start gap-6">
                        <p class="text-gray-700">
                            Items van de {{ module.name?.['en'] }} module. Als de module toegevoegd is aan het
                            profiel zijn de items zichtbaar in het menu.
                        </p>
                        <Button :label="moduleItemCreateButton.label" :href="moduleItemCreateButton.url ?? ''"
                            :icon="moduleItemCreateButton.icon" />
                    </div>
                    <OrderedTable v-if="table && module" :rows="table.data" :columns="table.columns" only="table"
                        :reorder-url="reorderUrl" />
                </div>
            </TabItem>
            <TabItem name="permissions" label="Toegang" v-if="module">
                <div class="flex justify-between items-center">
                    <p>
                        Regels voor lees en schrijfrechten voor de {{ module.name?.['en'] }} module.
                    </p>
                </div>
            </TabItem>
            <TabItem name="profiles" label="Profielen" v-if="module">
                <div class="flex justify-between items-center">
                    <p>
                        Profielen van gebruikers waar de {{ module.name?.['en'] }} module aan toegevoegd is.
                    </p>
                </div>
            </TabItem>
            <TabItem name="management" label="Beheer" v-if="module">
                <Button :label="destroyButton.label" :icon="destroyButton.icon" theme="danger" type="button"
                    class="col-span-2 mt-4" :href="destroyButton.url ?? ''" method="delete" />
            </TabItem>
        </TabBar>
    </div>
</template>
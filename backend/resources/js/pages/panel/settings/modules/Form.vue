<script lang="ts" setup>
import { Form, Link } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import PanelLayout from '@/layouts/PanelLayout.vue';
import type { Module } from '@/types/custom';
import { update, destroy } from '@/routes/panel/settings/modules'
import Button from '@/components/ui/form/Button.vue';
import TabBar from '@/components/ui/TabBar.vue';
import TabItem from '@/components/ui/TabItem.vue';
import Input from '@/components/ui/form/Input.vue';
import InputError from '@/components/ui/form/InputError.vue';

defineOptions({
    layout: PanelLayout,
});

const props = defineProps<{
    module: Module;
}>();

const name = ref<Record<string, string>>(JSON.parse(JSON.stringify(props.module.name)));
const icon = ref<string | null>(props.module.icon);

const hasChanges = computed(() => {
    return JSON.stringify(name.value) !== JSON.stringify(props.module.name) || icon.value !== props.module.icon;
});

const saveButton = {
    label: 'Wijzigingen opslaan',
    icon: 'fa-regular fa-save',
};

const destroyButton = {
    label: 'Module verwijderen',
    icon: 'fa-regular fa-trash',
    url: destroy.url({ module: props.module.id }),
};
</script>

<template>
    <div class="flex flex-col gap-4">
        <TabBar>
            <TabItem name="Gegevens">
                <Form v-bind="update.form({ module: module.id })"
                    v-slot="{ errors, processing, recentlySuccessful, clearErrors }"
                    class="grid grid-cols-4 gap-2 gap-y-4 bg-gray-100 p-4 rounded-md">
                    <div class="flex flex-col gap-2 col-span-2">
                        <Input type="text" name="name[en]" v-model="name['en']" @input="clearErrors('name.en')"
                            label="Engelse naam" />
                        <InputError :message="errors['name.en']" />
                    </div>
                    <div class="flex flex-col gap-2 col-span-2">
                        <Input type="text" name="name[nl]" v-model="name['nl']" @input="clearErrors('name.nl')"
                            label="Nederlandse naam" />
                        <InputError :message="errors['name.nl']" />
                    </div>
                    <div class="flex flex-col gap-2 col-span-4">
                        <label for="icon">Pictogram</label>
                        <div class="flex items-center gap-2">
                            <i :class="`fa-regular ${icon}`" class="text-gray-500 text-2xl"></i>
                            <div class="flex flex-col gap-2 flex-1">
                                <Input type="text" name="icon" v-model="icon" @input="clearErrors('icon')"
                                    placeholder="fa-icon" />
                            </div>
                        </div>
                        <p v-show="errors.icon" class="text-red-500 ml-10">{{ errors.icon }}</p>
                    </div>
                    <div class="col-span-4 flex flex-col gap-2 mt-4 items-end">
                        <Button :label="saveButton.label" :icon="saveButton.icon" type="submit"
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
            <TabItem name="Items">
                <div class="flex justify-between items-center">
                    <p>
                        Items van de {{ module.name?.['en'] }} module bewerken. Als de module toegevoegd is aan het
                        profiel zijn de items zichtbaar in de sidebar.
                    </p>
                </div>
            </TabItem>
            <TabItem name="Profielen">
                <div class="flex justify-between items-center">
                    <p>
                        Profielen waar de {{ module.name?.['en'] }} module aan toegevoegd is bewerken.
                    </p>
                </div>
            </TabItem>
            <TabItem name="Beheer">
                <Button :label="destroyButton.label" :icon="destroyButton.icon" :style="'danger'" type="button" class="col-span-2 mt-4" :url="destroyButton.url" method="delete" />
            </TabItem>
        </TabBar>
    </div>
</template>
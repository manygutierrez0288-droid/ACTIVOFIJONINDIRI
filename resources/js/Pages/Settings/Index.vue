<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const props = defineProps({
    settings: Object,
});

const form = useForm({
    ...Object.keys(props.settings).reduce((acc, group) => {
        props.settings[group].forEach(setting => {
            acc[setting.key] = setting.value;
        });
        return acc;
    }, {})
});

const updateSettings = () => {
    form.post(route('settings.update'), {
        preserveScroll: true,
        onSuccess: () => {
            // Toast handled by layout
        },
    });
};

const handleImageUpload = (key, event) => {
    form[key] = event.target.files[0];
};
</script>

<template>
    <Head title="Configuración" />

    <AuthenticatedLayout>
        <template #header>
            Configuración del Sistema
        </template>

        <div class="space-y-6">
            <div v-for="(groupSettings, groupName) in settings" :key="groupName" class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 capitalize mb-4">
                    {{ groupName === 'general' ? 'General' : (groupName === 'reports' ? 'Reportes' : groupName) }}
                </h3>

                <div class="grid grid-cols-1 gap-6">
                    <div v-for="setting in groupSettings" :key="setting.id">
                        <InputLabel :for="setting.key" :value="setting.label" />
                        
                        <div v-if="setting.type === 'image'" class="mt-1">
                            <input 
                                type="file" 
                                @change="handleImageUpload(setting.key, $event)"
                                class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 dark:file:bg-gray-700 dark:file:text-gray-300"
                            />
                            <div v-if="setting.value && typeof setting.value === 'string'" class="mt-2">
                                <img :src="'/storage/' + setting.value" class="h-16 object-contain" />
                            </div>
                        </div>

                        <TextInput
                            v-else
                            :id="setting.key"
                            :type="setting.type === 'number' ? 'number' : 'text'"
                            v-model="form[setting.key]"
                            class="mt-1 block w-full"
                        />
                    </div>
                </div>
            </div>

            <div class="flex justify-end">
                <PrimaryButton @click="updateSettings" :disabled="form.processing">
                    Guardar Cambios
                </PrimaryButton>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

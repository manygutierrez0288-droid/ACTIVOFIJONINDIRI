<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';
import TextInput from '@/Components/TextInput.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const props = defineProps({
    role: Object,
});

const form = useForm({
    name: props.role.name,
    description: props.role.description,
});

const submit = () => {
    form.put(route('roles.update', props.role.id));
};
</script>

<template>
    <Head title="Editar Rol" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Editar Rol</h2>
        </template>

        
            
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <form @submit.prevent="submit">
                        <div>
                            <InputLabel for="name" value="Nombre" />
                            <TextInput id="name" type="text" class="mt-1 block w-full" v-model="form.name" required autofocus />
                            <InputError class="mt-2" :message="form.errors.name" />
                        </div>

                        <div class="mt-4">
                            <InputLabel for="description" value="Descripción" />
                            <TextInput id="description" type="text" class="mt-1 block w-full" v-model="form.description" />
                            <InputError class="mt-2" :message="form.errors.description" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <Link :href="route('roles.index')" class="text-gray-600 dark:text-gray-400 underline text-sm hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 mr-4">
                                Cancelar
                            </Link>

                            <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                Actualizar
                            </PrimaryButton>
                        </div>
                    </form>
                </div></AuthenticatedLayout>
</template>



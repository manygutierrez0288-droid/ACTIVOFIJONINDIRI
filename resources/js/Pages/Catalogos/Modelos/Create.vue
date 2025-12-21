<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';
import TextInput from '@/Components/TextInput.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

defineProps({ marcas: Array });

const form = useForm({
    nombre: '',
    marca_id: ''
});

const submit = () => form.post(route('modelos.store'));
</script>

<template>
    <Head title="Crear Modelo" />
    <AuthenticatedLayout>
        <template #header><h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Crear Modelo</h2></template>
        <div class="py-12"><div class="max-w-7xl mx-auto sm:px-6 lg:px-8"><div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
            <form @submit.prevent="submit">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div><InputLabel for="nombre" value="Nombre del Modelo" /><TextInput id="nombre" class="mt-1 block w-full" v-model="form.nombre" required /><InputError :message="form.errors.nombre" /></div>
                    <div>
                        <InputLabel for="marca_id" value="Marca" />
                        <select id="marca_id" v-model="form.marca_id" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                            <option value="">Seleccione una marca</option>
                            <option v-for="marca in marcas" :key="marca.id" :value="marca.id">{{ marca.nombre }}</option>
                        </select>
                        <InputError :message="form.errors.marca_id" />
                    </div>
                </div>
                <div class="flex justify-end mt-4"><Link :href="route('modelos.index')" class="mr-4 underline text-gray-600">Cancelar</Link><PrimaryButton :disabled="form.processing">Guardar</PrimaryButton></div>
            </form>
        </div></div></div>
    </AuthenticatedLayout>
</template>

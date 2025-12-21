<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';
import TextInput from '@/Components/TextInput.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const props = defineProps({ ubicacion: Object });
const form = useForm({ nombre: props.ubicacion.nombre, descripcion: props.ubicacion.descripcion, activo: props.ubicacion.activo });
const submit = () => form.put(route('ubicaciones.update', props.ubicacion.id));
</script>
<template>
    <Head title="Editar Ubicación" />
    <AuthenticatedLayout>
        <template #header><h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Editar Ubicación</h2></template>
        <div class="py-12"><div class="max-w-7xl mx-auto sm:px-6 lg:px-8"><div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
            <form @submit.prevent="submit">
                <div><InputLabel for="nombre" value="Nombre" /><TextInput id="nombre" class="mt-1 block w-full" v-model="form.nombre" required /><InputError :message="form.errors.nombre" /></div>
                <div class="mt-4"><InputLabel for="descripcion" value="Descripción" /><TextInput id="descripcion" class="mt-1 block w-full" v-model="form.descripcion" /><InputError :message="form.errors.descripcion" /></div>
                <div class="flex justify-end mt-4"><Link :href="route('ubicaciones.index')" class="mr-4 underline text-gray-600">Cancelar</Link><PrimaryButton :disabled="form.processing">Actualizar</PrimaryButton></div>
            </form>
        </div></div></div>
    </AuthenticatedLayout>
</template>

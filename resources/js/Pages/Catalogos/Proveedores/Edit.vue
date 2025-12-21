<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';
import TextInput from '@/Components/TextInput.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const props = defineProps({ proveedor: Object });

const form = useForm({
    nombre: props.proveedor.nombre,
    numero_ruc: props.proveedor.numero_ruc,
    telefono: props.proveedor.telefono,
    email: props.proveedor.email,
    direccion: props.proveedor.direccion,
    activo: props.proveedor.activo
});

const submit = () => form.put(route('proveedores.update', props.proveedor.id));
</script>

<template>
    <Head title="Editar Proveedor" />
    <AuthenticatedLayout>
        <template #header><h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Editar Proveedor</h2></template>
        <div class="py-12"><div class="max-w-7xl mx-auto sm:px-6 lg:px-8"><div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
            <form @submit.prevent="submit">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div><InputLabel for="nombre" value="Nombre" /><TextInput id="nombre" class="mt-1 block w-full" v-model="form.nombre" required /><InputError :message="form.errors.nombre" /></div>
                    <div><InputLabel for="numero_ruc" value="RUC/ID" /><TextInput id="numero_ruc" class="mt-1 block w-full" v-model="form.numero_ruc" /><InputError :message="form.errors.numero_ruc" /></div>
                    <div><InputLabel for="telefono" value="Teléfono" /><TextInput id="telefono" class="mt-1 block w-full" v-model="form.telefono" /><InputError :message="form.errors.telefono" /></div>
                    <div><InputLabel for="email" value="Email" /><TextInput id="email" type="email" class="mt-1 block w-full" v-model="form.email" /><InputError :message="form.errors.email" /></div>
                    <div class="md:col-span-2"><InputLabel for="direccion" value="Dirección" /><TextInput id="direccion" class="mt-1 block w-full" v-model="form.direccion" /><InputError :message="form.errors.direccion" /></div>
                </div>
                <div class="flex justify-end mt-4"><Link :href="route('proveedores.index')" class="mr-4 underline text-gray-600">Cancelar</Link><PrimaryButton :disabled="form.processing">Actualizar</PrimaryButton></div>
            </form>
        </div></div></div>
    </AuthenticatedLayout>
</template>

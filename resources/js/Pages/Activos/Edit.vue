<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';
import TextInput from '@/Components/TextInput.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const props = defineProps({
    activo: Object,
    categorias: Array,
    departamentos: Array,
    ubicaciones: Array,
});

const form = useForm({
    nombre: props.activo.nombre,
    categoria_id: props.activo.categoria_id || '',
    departamento_id: props.activo.departamento_id || '',
    ubicacion_id: props.activo.ubicacion_id || '',
    fecha_adquisicion: props.activo.fecha_adquisicion || '',
    valor_adquisicion: props.activo.valor_adquisicion || '',
    estado: props.activo.estado || 'activo',
});

const submit = () => form.put(route('activos.update', props.activo.id));
</script>

<template>
    <Head title="Editar Activo Fijo" />
    <AuthenticatedLayout>
        <template #header><h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Editar Activo Fijo</h2></template>
        <div class="py-12"><div class="max-w-7xl mx-auto sm:px-6 lg:px-8"><div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
            <form @submit.prevent="submit" class="space-y-4">
                <div>
                    <InputLabel for="nombre" value="Nombre del Activo" />
                    <TextInput id="nombre" class="mt-1 block w-full" v-model="form.nombre" required />
                    <InputError :message="form.errors.nombre" />
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <InputLabel for="categoria_id" value="Categoría" />
                        <select id="categoria_id" v-model="form.categoria_id" class="mt-1 block w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            <option value="">Seleccionar...</option>
                            <option v-for="cat in categorias" :key="cat.id" :value="cat.id">{{ cat.nombre }}</option>
                        </select>
                        <InputError :message="form.errors.categoria_id" />
                    </div>
                    <div>
                        <InputLabel for="departamento_id" value="Departamento" />
                        <select id="departamento_id" v-model="form.departamento_id" class="mt-1 block w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            <option value="">Seleccionar...</option>
                            <option v-for="dep in departamentos" :key="dep.id" :value="dep.id">{{ dep.nombre }}</option>
                        </select>
                        <InputError :message="form.errors.departamento_id" />
                    </div>
                    <div>
                        <InputLabel for="ubicacion_id" value="Ubicación" />
                        <select id="ubicacion_id" v-model="form.ubicacion_id" class="mt-1 block w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            <option value="">Seleccionar...</option>
                            <option v-for="ubi in ubicaciones" :key="ubi.id" :value="ubi.id">{{ ubi.nombre }}</option>
                        </select>
                        <InputError :message="form.errors.ubicacion_id" />
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <InputLabel for="fecha_adquisicion" value="Fecha de Adquisición" />
                        <TextInput id="fecha_adquisicion" type="date" class="mt-1 block w-full" v-model="form.fecha_adquisicion" />
                        <InputError :message="form.errors.fecha_adquisicion" />
                    </div>
                    <div>
                        <InputLabel for="valor_adquisicion" value="Valor de Adquisición" />
                        <TextInput id="valor_adquisicion" type="number" step="0.01" class="mt-1 block w-full" v-model="form.valor_adquisicion" />
                        <InputError :message="form.errors.valor_adquisicion" />
                    </div>
                </div>

                <div class="flex justify-end mt-6">
                    <Link :href="route('activos.index')" class="mr-4 underline text-gray-600 dark:text-gray-400">Cancelar</Link>
                    <PrimaryButton :disabled="form.processing">Actualizar</PrimaryButton>
                </div>
            </form>
        </div></div></div>
    </AuthenticatedLayout>
</template>

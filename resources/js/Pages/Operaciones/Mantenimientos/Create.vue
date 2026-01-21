<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import TextInput from '@/Components/TextInput.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const props = defineProps({
    activo: Object,
    tecnicos: Array,
    proveedores: Array,
    estados: Array,
});

const form = useForm({
    activo_fijo_id: props.activo.id,
    fecha: new Date().toISOString().substring(0, 10),
    descripcion: '',
    costo: 0,
    tecnico_id: '',
    proveedor_id: '',
    estado_id: props.activo.estado_id,
});

const submit = () => form.post(route('mantenimientos.store'));
</script>

<template>
    <Head title="Registrar Mantenimiento" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Mantenimiento: {{ activo.nombre }}
            </h2>
        </template>

        
            
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-8">
                    <form @submit.prevent="submit" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <InputLabel for="fecha" value="Fecha del Mantenimiento" />
                                <TextInput id="fecha" type="date" v-model="form.fecha" class="mt-1 block w-full" required />
                                <InputError :message="form.errors.fecha" />
                            </div>
                            <div>
                                <InputLabel for="costo" value="Costo (NIO)" />
                                <TextInput id="costo" type="number" step="0.01" v-model="form.costo" class="mt-1 block w-full" required />
                                <InputError :message="form.errors.costo" />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <InputLabel for="tecnico_id" value="Técnico Encargado" />
                                <select id="tecnico_id" v-model="form.tecnico_id" class="mt-1 block w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                    <option value="">Interno/No especificado</option>
                                    <option v-for="item in tecnicos" :key="item.id" :value="item.id">{{ item.nombre }}</option>
                                </select>
                                <InputError :message="form.errors.tecnico_id" />
                            </div>
                            <div>
                                <InputLabel for="proveedor_id" value="Empresa Proveedora" />
                                <select id="proveedor_id" v-model="form.proveedor_id" class="mt-1 block w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                    <option value="">Personal Interno</option>
                                    <option v-for="item in proveedores" :key="item.id" :value="item.id">{{ item.nombre }}</option>
                                </select>
                                <InputError :message="form.errors.proveedor_id" />
                            </div>
                             <div>
                                <InputLabel for="estado_id" value="Estado Posterior" />
                                <select id="estado_id" v-model="form.estado_id" class="mt-1 block w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>
                                    <option v-for="item in estados" :key="item.id" :value="item.id">{{ item.nombre }}</option>
                                </select>
                                <InputError :message="form.errors.estado_id" />
                            </div>
                        </div>

                        <div>
                            <InputLabel for="descripcion" value="Trabajos Realizados" />
                            <textarea id="descripcion" v-model="form.descripcion" rows="4" class="mt-1 block w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white" required placeholder="Ej: Cambio de batería, limpieza de ventiladores, cambio de pasta térmica..."></textarea>
                            <InputError :message="form.errors.descripcion" />
                        </div>

                        <div class="flex justify-end gap-4 border-t pt-6 dark:border-gray-700">
                            <Link :href="route('activos.show', activo.id)" class="px-4 py-2 text-gray-700 dark:text-gray-300">Cancelar</Link>
                            <PrimaryButton :disabled="form.processing">Registrar Mantenimiento</PrimaryButton>
                        </div>
                    </form>
                </div></AuthenticatedLayout>
</template>



<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import TextInput from '@/Components/TextInput.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const props = defineProps({
    activo: Object,
    ubicaciones: Array,
    responsables: Array,
});

const form = useForm({
    activo_fijo_id: props.activo.id,
    ubicacion_destino_id: '',
    responsable_destino_id: '',
    fecha: new Date().toISOString().substring(0, 10),
    motivo: '',
});

const submit = () => form.post(route('movimientos.store'));
</script>

<template>
    <Head title="Registrar Movimiento" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Traslado de Activo: {{ activo.nombre }}
            </h2>
        </template>

        
            
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-8">
                    <div class="mb-8 p-4 bg-indigo-50 dark:bg-indigo-900/20 rounded-xl border border-indigo-100 dark:border-indigo-800">
                        <h3 class="text-indigo-800 dark:text-indigo-300 font-bold mb-2">Estado Actual</h3>
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <p class="text-gray-500">Ubicación:</p>
                                <p class="font-semibold text-gray-800 dark:text-gray-200">{{ activo.ubicacion?.nombre || 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-gray-500">Responsable:</p>
                                <p class="font-semibold text-gray-800 dark:text-gray-200">{{ activo.responsable?.nombre || 'N/A' }}</p>
                            </div>
                        </div>
                    </div>

                    <form @submit.prevent="submit" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <InputLabel for="ubicacion_destino_id" value="Nueva Ubicación (Destino)" />
                                <select id="ubicacion_destino_id" v-model="form.ubicacion_destino_id" class="mt-1 block w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>
                                    <option value="">Seleccionar...</option>
                                    <option v-for="ubi in ubicaciones" :key="ubi.id" :value="ubi.id">{{ ubi.nombre }}</option>
                                </select>
                                <InputError :message="form.errors.ubicacion_destino_id" />
                            </div>

                            <div>
                                <InputLabel for="responsable_destino_id" value="Nuevo Responsable" />
                                <select id="responsable_destino_id" v-model="form.responsable_destino_id" class="mt-1 block w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>
                                    <option value="">Seleccionar...</option>
                                    <option v-for="res in responsables" :key="res.id" :value="res.id">{{ res.nombre }}</option>
                                </select>
                                <InputError :message="form.errors.responsable_destino_id" />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <InputLabel for="fecha" value="Fecha del Traslado" />
                                <TextInput id="fecha" type="date" v-model="form.fecha" class="mt-1 block w-full" required />
                                <InputError :message="form.errors.fecha" />
                            </div>
                        </div>

                        <div>
                            <InputLabel for="motivo" value="Motivo o Justificación" />
                            <textarea id="motivo" v-model="form.motivo" rows="3" class="mt-1 block w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white" required placeholder="Ej: Traslado a nueva oficina por remodelación..."></textarea>
                            <InputError :message="form.errors.motivo" />
                        </div>

                        <div class="flex justify-end gap-4 border-t pt-6 dark:border-gray-700">
                            <Link :href="route('activos.show', activo.id)" class="px-4 py-2 text-gray-700 dark:text-gray-300">Cancelar</Link>
                            <PrimaryButton :disabled="form.processing">Registrar Traslado</PrimaryButton>
                        </div>
                    </form>
                </div></AuthenticatedLayout>
</template>



<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import TextInput from '@/Components/TextInput.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const props = defineProps({
    activo: Object,
    estados: Array,
});

const form = useForm({
    activo_fijo_id: props.activo.id,
    fecha: new Date().toISOString().substring(0, 10),
    motivo: '',
    documento_respaldo: '',
});

const submit = () => form.post(route('bajas.store'));
</script>

<template>
    <Head title="Baja de Activo" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Dar de Baja Activo: {{ activo.nombre }}
            </h2>
        </template>

        
            
                <div class="bg-red-50 dark:bg-red-900/10 border border-red-200 dark:border-red-800 p-6 rounded-xl mb-6 flex items-start gap-4">
                    <div class="bg-red-100 dark:bg-red-900/40 p-2 rounded-lg text-red-600 dark:text-red-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    </div>
                    <div>
                        <h4 class="text-red-800 dark:text-red-300 font-bold">¡Atención!</h4>
                        <p class="text-sm text-red-700 dark:text-red-400">Esta acción retirará el activo del inventario activo de la municipalidad. Esta operación debe estar respaldada por un acta técnica.</p>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-8">
                    <form @submit.prevent="submit" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <InputLabel for="fecha" value="Fecha de la Baja" />
                                <TextInput id="fecha" type="date" v-model="form.fecha" class="mt-1 block w-full" required />
                                <InputError :message="form.errors.fecha" />
                            </div>
                            <div class="flex items-end">
                                <div class="p-3 bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-300 rounded-xl text-xs font-bold w-full border border-blue-100 dark:border-blue-800">
                                    ℹ️ El número de acta correlativo será generado automáticamente por el sistema al finalizar.
                                </div>
                            </div>
                        </div>

                        <div>
                            <InputLabel for="motivo" value="Causa de la Baja" />
                            <textarea id="motivo" v-model="form.motivo" rows="4" class="mt-1 block w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white" required placeholder="Describa por qué se da de baja (Ej: Daño irreparable, robo, pérdida, obsolescencia...)"></textarea>
                            <InputError :message="form.errors.motivo" />
                        </div>

                        <div class="flex justify-end gap-4 border-t pt-6 dark:border-gray-700">
                            <Link :href="route('activos.show', activo.id)" class="px-4 py-2 text-gray-700 dark:text-gray-300">Cancelar</Link>
                            <PrimaryButton class="!bg-red-600 hover:!bg-red-700" :disabled="form.processing">Confirmar Baja Definitiva</PrimaryButton>
                        </div>
                    </form>
                </div></AuthenticatedLayout>
</template>



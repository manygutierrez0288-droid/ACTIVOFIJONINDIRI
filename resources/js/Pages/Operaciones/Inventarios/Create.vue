<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ClipboardList, Save, Calendar, Info } from 'lucide-vue-next';

const form = useForm({
    nombre: '',
    fecha_inicio: new Date().toISOString().substr(0, 10),
    observaciones: '',
});

const submit = () => {
    form.post(route('inventarios.store'));
};
</script>

<template>
    <Head title="Iniciar Nuevo Inventario" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Iniciar Nuevo Inventario</h2>
        </template>

        <div class="py-12">
            <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-xl">
                    <div class="p-8">
                        <div class="flex items-center gap-4 mb-8">
                            <div class="p-3 bg-indigo-50 dark:bg-indigo-900/30 rounded-2xl">
                                <ClipboardList class="w-8 h-8 text-indigo-600 dark:text-indigo-400" />
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-900 dark:text-white">Configuración de Sesión</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Se realizará una captura de todos los activos actuales para su verificación física.</p>
                            </div>
                        </div>

                        <form @submit.prevent="submit" class="space-y-6">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1">Nombre de la Sesión</label>
                                <input v-model="form.nombre" type="text" placeholder="Ej: Inventario General Anual 2025" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white focus:ring-indigo-500" required />
                                <div v-if="form.errors.nombre" class="text-red-500 text-xs mt-1">{{ form.errors.nombre }}</div>
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1">Fecha de Inicio</label>
                                <div class="relative">
                                    <Calendar class="absolute left-3 top-2.5 w-4 h-4 text-gray-400" />
                                    <input v-model="form.fecha_inicio" type="date" class="w-full pl-10 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white focus:ring-indigo-500" required />
                                </div>
                                <div v-if="form.errors.fecha_inicio" class="text-red-500 text-xs mt-1">{{ form.errors.fecha_inicio }}</div>
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1">Observaciones / Alcance</label>
                                <textarea v-model="form.observaciones" rows="3" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white focus:ring-indigo-500" placeholder="Opcional: Detalle áreas o departamentos a cubrir..."></textarea>
                            </div>

                            <div class="bg-blue-50 dark:bg-blue-900/20 p-4 rounded-lg flex gap-3 border border-blue-100 dark:border-blue-900/30">
                                <Info class="w-5 h-5 text-blue-600 shrink-0" />
                                <p class="text-xs text-blue-700 dark:text-blue-300 leading-relaxed">
                                    Al iniciar esta sesión, el sistema generará una lista de chequeo con todos los activos fijos registrados hasta este momento. Podrá verificar su estado físico y ubicación real mediante el escaneo de sus etiquetas QR.
                                </p>
                            </div>

                            <div class="flex items-center justify-end gap-4 pt-4 border-t border-gray-100 dark:border-gray-700">
                                <button type="submit" :disabled="form.processing" class="inline-flex items-center px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-lg transition-all shadow-md hover:shadow-lg disabled:opacity-50">
                                    <Save class="w-4 h-4 mr-2" />
                                    Iniciar Proceso
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    backups: Array,
});

const form = useForm({});
const processing = ref(false);

const createBackup = () => {
    processing.value = true;
    form.post(route('backups.create'), {
        onFinish: () => processing.value = false,
    });
};

const deleteBackup = (filename) => {
    if (confirm('¿Estás seguro de eliminar este respaldo?')) {
        form.delete(route('backups.destroy', filename));
    }
};
</script>

<template>
    <Head title="Gestión de Respaldos" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Respaldos del Sistema
                </h2>
                <button
                    @click="createBackup"
                    :disabled="processing"
                    class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                    :class="{ 'opacity-25': processing }"
                >
                    <span v-if="processing">Generando...</span>
                    <span v-else>Generar Respaldo Ahora</span>
                </button>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="mb-4">
                        <p class="text-sm text-gray-600">
                            Aquí puedes ver y descargar los respaldos generados automáticamente o de forma manual. 
                            Se conservan los últimos 7 días de respaldos.
                        </p>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-widest">Nombre del Archivo</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-widest">Fecha</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-widest">Tamaño</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-widest">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-for="backup in backups" :key="backup.name">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ backup.name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ backup.date }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ backup.size }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                        <a
                                            :href="route('backups.download', backup.name)"
                                            class="text-indigo-600 hover:text-indigo-900"
                                        >Descargar</a>
                                        <button
                                            @click="deleteBackup(backup.name)"
                                            class="text-red-600 hover:text-red-900 ml-4"
                                        >Eliminar</button>
                                    </td>
                                </tr>
                                <tr v-if="backups.length === 0">
                                    <td colspan="4" class="px-6 py-10 text-center text-gray-500">
                                        No hay respaldos disponibles todavía.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

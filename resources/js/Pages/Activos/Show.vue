<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import AuditHistory from '@/Components/AuditHistory.vue';
import { Head, Link } from '@inertiajs/vue3';

const props = defineProps({
    activo: Object,
    auditorias: Array,
});

const formatCurrency = (value) => {
    return new Intl.NumberFormat('es-NI', {
        style: 'currency',
        currency: 'NIO',
    }).format(value);
};
</script>

<template>
    <Head title="Detalles del Activo" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Detalle del Activo Fijo: {{ activo.nombre }}
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex flex-wrap gap-2 mb-8">
                            <Link :href="route('activos.index', { edit_id: props.activo.id })" class="px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white rounded-lg text-sm font-medium transition-colors shadow-lg shadow-amber-200 dark:shadow-none">
                                Editar Datos
                            </Link>
                            <Link :href="route('activos.historial', { activo: props.activo.id })" class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg text-sm font-medium transition-colors">
                                Historial de Cambios
                            </Link>
                            <Link :href="route('movimientos.create', { activo: props.activo.id })" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium transition-colors">
                                Traslado
                            </Link>
                            <Link :href="route('mantenimientos.create', { activo: props.activo.id })" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg text-sm font-medium transition-colors">
                                Mantenimiento
                            </Link>
                            <Link :href="route('bajas.create', { activo: props.activo.id })" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg text-sm font-medium transition-colors">
                                Dar de Baja
                            </Link>
                            <a :href="route('activos.print', { activoFijo: props.activo.id })" target="_blank" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm font-medium transition-colors">
                                Imprimir Ficha
                            </a>
                            <Link :href="route('activos.index')" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg text-sm font-medium hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">Volver</Link>
                        </div>
                        
                        <div class="flex flex-col md:flex-row gap-8 mb-8">
                            <div v-if="activo.imagen_url" class="w-full md:w-1/3">
                                <img :src="activo.imagen_url" class="w-full h-64 object-cover rounded-2xl shadow-lg border-4 border-white dark:border-gray-700" alt="Foto del activo">
                            </div>
                            <div class="flex-1 grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Código de Inventario</p>
                                    <p class="font-bold text-xl text-indigo-600 dark:text-indigo-400 font-mono">{{ activo.codigo_inventario || 'No asignado' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">ID del Sistema</p>
                                    <p class="font-semibold text-gray-800 dark:text-gray-200">#{{ activo.id }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Categoría</p>
                                    <p class="font-semibold text-gray-800 dark:text-gray-200">{{ activo.categoria }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Ubicación</p>
                                    <p class="font-semibold text-gray-800 dark:text-gray-200">{{ activo.ubicacion }} - {{ activo.departamento }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Responsable</p>
                                    <p class="font-semibold text-gray-800 dark:text-gray-200">{{ activo.responsable || 'No asignado' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Marca / Modelo</p>
                                    <p class="font-semibold text-gray-800 dark:text-gray-200">{{ activo.marca || '-' }} / {{ activo.modelo || '-' }}</p>
                                </div>
                                 <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Estado</p>
                                    <span class="px-2 py-1 text-xs rounded bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300">
                                        {{ activo.estado_nombre }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <hr class="border-gray-200 dark:border-gray-700 mb-6">

                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Detalle Financiero y Depreciación</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                             <div class="bg-gray-50 dark:bg-gray-700/50 p-4 rounded-lg">
                                <p class="text-sm text-gray-500 dark:text-gray-400">Fecha Adquisición</p>
                                <p class="font-bold text-gray-800 dark:text-white">{{ activo.fecha_adquisicion }}</p>
                                <p class="text-xs text-gray-500 mt-1">Hace {{ activo.meses_depreciados }} meses</p>
                            </div>
                            <div class="bg-gray-50 dark:bg-gray-700/50 p-4 rounded-lg">
                                <p class="text-sm text-gray-500 dark:text-gray-400">Valor Adquisición</p>
                                <p class="font-bold text-gray-800 dark:text-white">{{ formatCurrency(activo.valor_adquisicion) }}</p>
                            </div>
                             <div class="bg-gray-50 dark:bg-gray-700/50 p-4 rounded-lg">
                                <p class="text-sm text-gray-500 dark:text-gray-400">Valor Residual</p>
                                <p class="font-bold text-gray-800 dark:text-white">{{ formatCurrency(activo.valor_residual) }}</p>
                            </div>
                        </div>

                        <div class="mt-6 grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div class="p-4 border border-gray-100 dark:border-gray-700 rounded-lg">
                                <p class="text-xs text-center text-gray-500 uppercase">Vida Útil</p>
                                <p class="text-xl text-center font-bold text-gray-800 dark:text-white">{{ activo.vida_util_anios }} Años</p>
                            </div>
                            <div class="p-4 border border-gray-100 dark:border-gray-700 rounded-lg">
                                <p class="text-xs text-center text-gray-500 uppercase">Depreciación Anual</p>
                                <p class="text-xl text-center font-bold text-blue-600 dark:text-blue-400">{{ formatCurrency(activo.depreciacion_anual) }}</p>
                            </div>
                             <div class="p-4 border border-gray-100 dark:border-gray-700 rounded-lg">
                                <p class="text-xs text-center text-gray-500 uppercase">Depreciación Mensual</p>
                                <p class="text-xl text-center font-bold text-blue-600 dark:text-blue-400">{{ formatCurrency(activo.depreciacion_mensual) }}</p>
                            </div>
                            <div class="p-4 border border-gray-100 dark:border-gray-700 rounded-lg bg-blue-50 dark:bg-blue-900/10">
                                <p class="text-xs text-center text-blue-800 dark:text-blue-300 uppercase font-bold">Valor Neto Actual</p>
                                <p class="text-2xl text-center font-extrabold text-green-600 dark:text-green-400">{{ formatCurrency(activo.valor_neto) }}</p>
                                <p class="text-xs text-center text-red-500 mt-1">Dep. Acum: {{ formatCurrency(activo.depreciacion_acumulada) }}</p>
                            </div>
                        </div>

                        <!-- Audit History Section -->
                        <hr class="border-gray-200 dark:border-gray-700 my-6">
                        <AuditHistory :auditorias="auditorias" />
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

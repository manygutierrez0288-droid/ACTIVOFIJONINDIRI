<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { Truck, Fuel, Users, Calendar, Gauge } from 'lucide-vue-next';

const props = defineProps({
    vehiculo: Object,
});

const formatCurrency = (value) => {
    return new Intl.NumberFormat('es-NI', {
        style: 'currency',
        currency: 'NIO',
    }).format(value);
};
</script>

<template>
    <Head title="Detalles del Vehículo" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Ficha Técnica de Vehículo
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <!-- Actions Toolbar -->
                        <div class="flex flex-wrap gap-2 mb-8">
                            <Link :href="route('vehiculos.index', { edit_id: props.vehiculo.id })" class="px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white rounded-lg text-sm font-medium transition-colors shadow-lg shadow-amber-200 dark:shadow-none">
                                Editar Datos
                            </Link>
                             <!-- Using Activo ID for history because AuditHistory is linked to ActivoFijo ID, checking Resource structure -->
                             <!-- VehiculoResource has 'activo_fijo_id'. We should use that for history route which expects 'activo' param -->
                            <Link :href="route('activos.historial', { activo: props.vehiculo.activo_fijo_id })" class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg text-sm font-medium transition-colors">
                                Historial de Cambios
                            </Link>
                            <a :href="route('vehiculos.print', props.vehiculo.id)" target="_blank" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm font-medium transition-colors flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                Imprimir Ficha
                            </a>
                            <Link :href="route('vehiculos.index')" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg text-sm font-medium hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">Volver</Link>
                        </div>
                        
                        <div class="flex flex-col md:flex-row gap-8 mb-8">
                            <div v-if="vehiculo.imagen_url" class="w-full md:w-1/3">
                                <img :src="vehiculo.imagen_url" class="w-full h-64 object-cover rounded-2xl shadow-lg border-4 border-white dark:border-gray-700" alt="Foto del vehículo">
                            </div>
                            <div class="flex-1 grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="col-span-1 md:col-span-2 flex items-center gap-3 bg-gray-50 dark:bg-gray-900/50 p-4 rounded-xl border border-gray-100 dark:border-gray-700">
                                    <div class="p-3 bg-yellow-400 text-black font-bold font-mono text-2xl rounded border-2 border-black shadow">
                                        {{ vehiculo.placa }}
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 uppercase font-bold">Vehículo</p>
                                        <h3 class="text-xl font-bold text-gray-900 dark:text-white">{{ vehiculo.nombre }}</h3>
                                    </div>
                                </div>

                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Código Inventario / ID</p>
                                    <p class="font-bold text-lg text-indigo-600 dark:text-indigo-400 font-mono">
                                        {{ vehiculo.codigo_inventario || 'N/A' }} <span class="text-xs text-gray-400 block">System ID: #{{ vehiculo.id }}</span>
                                    </p>
                                </div>
                                
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Departamento Responsable</p>
                                    <p class="font-semibold text-gray-800 dark:text-gray-200">{{ vehiculo.departamento }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Ubicación Física</p>
                                    <p class="font-semibold text-gray-800 dark:text-gray-200">{{ vehiculo.ubicacion }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Conductor / Responsable</p>
                                    <p class="font-semibold text-gray-800 dark:text-gray-200">{{ vehiculo.responsable || 'No asignado' }}</p>
                                </div>
                                 <div class="md:col-span-2">
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Estado Operativo</p>
                                    <span class="inline-flex mt-1 px-3 py-1 rounded-full text-sm font-bold uppercase tracking-wider" :class="[
                                    vehiculo.estado?.toLowerCase().includes('buen') ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-300' :
                                    vehiculo.estado?.toLowerCase().includes('mantenimiento') ? 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-300' :
                                    'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-300'
                                ]">
                                        {{ vehiculo.estado }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                            <div class="bg-blue-50 dark:bg-blue-900/20 p-4 rounded-xl flex items-center gap-3">
                                <Calendar class="w-8 h-8 text-blue-500" />
                                <div>
                                    <p class="text-xs text-blue-500 font-bold uppercase">Año</p>
                                    <p class="text-lg font-bold text-gray-800 dark:text-white">{{ vehiculo.anio }}</p>
                                </div>
                            </div>
                            <div class="bg-purple-50 dark:bg-purple-900/20 p-4 rounded-xl flex items-center gap-3">
                                <Gauge class="w-8 h-8 text-purple-500" />
                                <div>
                                    <p class="text-xs text-purple-500 font-bold uppercase">Kilometraje</p>
                                    <p class="text-lg font-bold text-gray-800 dark:text-white">{{ vehiculo.kilometraje }} km</p>
                                </div>
                            </div>
                             <div class="bg-orange-50 dark:bg-orange-900/20 p-4 rounded-xl flex items-center gap-3">
                                <Fuel class="w-8 h-8 text-orange-500" />
                                <div>
                                    <p class="text-xs text-orange-500 font-bold uppercase">Combustible</p>
                                    <p class="text-lg font-bold text-gray-800 dark:text-white">{{ vehiculo.tipo_combustible }}</p>
                                </div>
                            </div>
                             <div class="bg-teal-50 dark:bg-teal-900/20 p-4 rounded-xl flex items-center gap-3">
                                <Users class="w-8 h-8 text-teal-500" />
                                <div>
                                    <p class="text-xs text-teal-500 font-bold uppercase">Pasajeros</p>
                                    <p class="text-lg font-bold text-gray-800 dark:text-white">{{ vehiculo.capacidad_pasajeros }}</p>
                                </div>
                            </div>
                        </div>

                        <hr class="border-gray-200 dark:border-gray-700 mb-6">

                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Detalle Financiero</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                             <div class="bg-gray-50 dark:bg-gray-700/50 p-4 rounded-lg">
                                <p class="text-sm text-gray-500 dark:text-gray-400">Fecha Adquisición</p>
                                <p class="font-bold text-gray-800 dark:text-white">{{ vehiculo.fecha_adquisicion }}</p>
                            </div>
                            <div class="bg-gray-50 dark:bg-gray-700/50 p-4 rounded-lg">
                                <p class="text-sm text-gray-500 dark:text-gray-400">Valor Adquisición</p>
                                <p class="font-bold text-gray-800 dark:text-white">{{ formatCurrency(vehiculo.valor_adquisicion) }}</p>
                            </div>
                             <div class="bg-gray-50 dark:bg-gray-700/50 p-4 rounded-lg">
                                <p class="text-sm text-gray-500 dark:text-gray-400">Valor Neto Actual</p>
                                <p class="font-bold text-green-600 dark:text-green-400">{{ formatCurrency(vehiculo.valor_neto) }}</p>
                            </div>
                        </div>

                        <div class="mt-6 p-4 border border-gray-100 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-800">
                             <h4 class="font-bold text-gray-700 dark:text-gray-300 mb-2">Especificaciones Adicionales</h4>
                             <div class="grid grid-cols-2 text-sm">
                                <p><span class="font-semibold">Marca:</span> {{ vehiculo.marca }}</p>
                                <p><span class="font-semibold">Modelo:</span> {{ vehiculo.modelo }}</p>
                                <p><span class="font-semibold">Color:</span> {{ vehiculo.color }}</p>
                                <p><span class="font-semibold">Chasis/VIN:</span> {{ vehiculo.numero_serie || 'N/A' }}</p>
                                <p v-if="vehiculo.numero_circulacion" class="col-span-2"><span class="font-semibold">N° Circulación:</span> <span class="font-mono text-indigo-600 dark:text-indigo-400">{{ vehiculo.numero_circulacion }}</span></p>
                             </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

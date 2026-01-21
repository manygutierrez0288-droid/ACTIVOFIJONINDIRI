<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { ChevronLeft, Edit, MapPin, DollarSign, FileText } from 'lucide-vue-next';

const props = defineProps({
    terreno: Object,
});

const formatCurrency = (value) => {
    return new Intl.NumberFormat('es-NI', {
        style: 'currency',
        currency: 'NIO',
    }).format(value);
};

const formatArea = (value) => {
    return new Intl.NumberFormat('es-NI', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
    }).format(value) + ' m²';
};

const formatDate = (date) => {
    return new Date(date).toLocaleDateString('es-NI', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
};
</script>

<template>
    <Head :title="`Terreno ${terreno.numero_escritura}`" />
    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <Link :href="route('terrenos.index')" class="p-2 bg-white dark:bg-gray-800 rounded-lg shadow-sm hover:bg-gray-50 transition-colors">
                        <ChevronLeft class="w-5 h-5 text-gray-500" />
                    </Link>
                    <div>
                        <h2 class="font-bold text-2xl text-gray-900 dark:text-white">{{ terreno.activo_fijo?.nombre }}</h2>
                        <p class="text-sm text-gray-500">Escritura: {{ terreno.numero_escritura }}</p>
                    </div>
                </div>
                <Link :href="route('terrenos.edit', terreno.id)" class="flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg font-bold text-sm transition-all">
                    <Edit class="w-4 h-4" />
                    Editar
                </Link>
            </div>
        </template>

        <div class="max-w-6xl mx-auto space-y-6">
            <!-- Información Principal -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 bg-gradient-to-r from-indigo-50 to-purple-50 dark:from-indigo-900/20 dark:to-purple-900/20">
                    <h3 class="font-bold text-lg text-gray-900 dark:text-white">Información del Terreno</h3>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <p class="text-xs font-bold uppercase text-gray-500 mb-1">Número de Escritura</p>
                        <p class="text-lg font-bold text-indigo-600 dark:text-indigo-400 font-mono">{{ terreno.numero_escritura }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-bold uppercase text-gray-500 mb-1">Área Total</p>
                        <p class="text-lg font-bold text-emerald-600 dark:text-emerald-400">{{ formatArea(terreno.area_metros_cuadrados) }}</p>
                        <p v-if="terreno.frente_metros && terreno.fondo_metros" class="text-xs text-gray-500 mt-1">
                            {{ terreno.frente_metros }}m (frente) × {{ terreno.fondo_metros }}m (fondo)
                        </p>
                    </div>
                    <div>
                        <p class="text-xs font-bold uppercase text-gray-500 mb-1">Valor por m²</p>
                        <p class="text-lg font-bold text-amber-600 dark:text-amber-400">{{ formatCurrency(terreno.valor_por_metro_cuadrado) }}</p>
                    </div>
                    <div v-if="terreno.uso_suelo">
                        <p class="text-xs font-bold uppercase text-gray-500 mb-1">Uso de Suelo</p>
                        <p class="text-sm text-gray-900 dark:text-white">{{ terreno.uso_suelo }}</p>
                    </div>
                    <div v-if="terreno.zonificacion">
                        <p class="text-xs font-bold uppercase text-gray-500 mb-1">Zonificación</p>
                        <p class="text-sm text-gray-900 dark:text-white">{{ terreno.zonificacion }}</p>
                    </div>
                    <div v-if="terreno.valor_catastral">
                        <p class="text-xs font-bold uppercase text-gray-500 mb-1">Valor Catastral</p>
                        <p class="text-sm font-bold text-gray-900 dark:text-white">{{ formatCurrency(terreno.valor_catastral) }}</p>
                    </div>
                </div>
            </div>

            <!-- Linderos -->
            <div v-if="terreno.lindero_norte || terreno.lindero_sur || terreno.lindero_este || terreno.lindero_oeste" class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 bg-gradient-to-r from-purple-50 to-pink-50 dark:from-purple-900/20 dark:to-pink-900/20 flex items-center gap-3">
                    <MapPin class="w-5 h-5 text-purple-600 dark:text-purple-400" />
                    <h3 class="font-bold text-lg text-gray-900 dark:text-white">Linderos y Colindancias</h3>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div v-if="terreno.lindero_norte">
                        <p class="text-xs font-bold uppercase text-gray-500 mb-1">Norte</p>
                        <p class="text-sm text-gray-700 dark:text-gray-300">{{ terreno.lindero_norte }}</p>
                    </div>
                    <div v-if="terreno.lindero_sur">
                        <p class="text-xs font-bold uppercase text-gray-500 mb-1">Sur</p>
                        <p class="text-sm text-gray-700 dark:text-gray-300">{{ terreno.lindero_sur }}</p>
                    </div>
                    <div v-if="terreno.lindero_este">
                        <p class="text-xs font-bold uppercase text-gray-500 mb-1">Este</p>
                        <p class="text-sm text-gray-700 dark:text-gray-300">{{ terreno.lindero_este }}</p>
                    </div>
                    <div v-if="terreno.lindero_oeste">
                        <p class="text-xs font-bold uppercase text-gray-500 mb-1">Oeste</p>
                        <p class="text-sm text-gray-700 dark:text-gray-300">{{ terreno.lindero_oeste }}</p>
                    </div>
                </div>
            </div>

            <!-- Datos del Activo Fijo -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 bg-gradient-to-r from-amber-50 to-orange-50 dark:from-amber-900/20 dark:to-orange-900/20 flex items-center gap-3">
                    <DollarSign class="w-5 h-5 text-amber-600 dark:text-amber-400" />
                    <h3 class="font-bold text-lg text-gray-900 dark:text-white">Información Financiera</h3>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <p class="text-xs font-bold uppercase text-gray-500 mb-1">Valor de Adquisición</p>
                        <p class="text-xl font-bold text-gray-900 dark:text-white">{{ formatCurrency(terreno.activo_fijo?.valor_adquisicion) }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-bold uppercase text-gray-500 mb-1">Fecha de Adquisición</p>
                        <p class="text-sm text-gray-900 dark:text-white">{{ formatDate(terreno.activo_fijo?.fecha_adquisicion) }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-bold uppercase text-gray-500 mb-1">Código de Inventario</p>
                        <p class="text-sm font-mono font-bold text-gray-900 dark:text-white">{{ terreno.activo_fijo?.codigo_inventario || 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-bold uppercase text-gray-500 mb-1">Departamento</p>
                        <p class="text-sm text-gray-900 dark:text-white">{{ terreno.activo_fijo?.departamento?.nombre }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-bold uppercase text-gray-500 mb-1">Ubicación</p>
                        <p class="text-sm text-gray-900 dark:text-white">{{ terreno.activo_fijo?.ubicacion?.nombre }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-bold uppercase text-gray-500 mb-1">Estado</p>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold" :class="[
                            terreno.activo_fijo?.estado?.nombre?.toLowerCase().includes('activo') ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-300' :
                            'bg-gray-100 text-gray-700 dark:bg-gray-900/30 dark:text-gray-300'
                        ]">
                            {{ terreno.activo_fijo?.estado?.nombre }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Coordenadas GPS -->
            <div v-if="terreno.coordenadas_gps" class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 bg-gradient-to-r from-blue-50 to-cyan-50 dark:from-blue-900/20 dark:to-cyan-900/20">
                    <h3 class="font-bold text-lg text-gray-900 dark:text-white">Ubicación Geográfica</h3>
                </div>
                <div class="p-6">
                    <p class="text-xs font-bold uppercase text-gray-500 mb-2">Coordenadas GPS</p>
                    <p class="text-sm font-mono text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-900 p-3 rounded-lg border border-gray-200 dark:border-gray-700">
                        {{ terreno.coordenadas_gps }}
                    </p>
                    <a :href="`https://www.google.com/maps/search/?api=1&query=${terreno.coordenadas_gps}`" target="_blank" class="mt-3 inline-flex items-center gap-2 text-sm text-blue-600 hover:text-blue-700 font-medium">
                        <MapPin class="w-4 h-4" />
                        Ver en Google Maps
                    </a>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

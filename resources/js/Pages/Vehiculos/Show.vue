<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { 
    Truck, 
    Fuel, 
    Users, 
    Calendar, 
    Gauge, 
    ArrowLeft, 
    Printer, 
    Pencil, 
    History, 
    MapPin, 
    User, 
    Tag, 
    Info, 
    Wallet, 
    Clock, 
    DollarSign,
    Milestone
} from 'lucide-vue-next';

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
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h2 class="text-2xl font-black tracking-tight text-gray-900 dark:text-white uppercase">
                        Ficha Técnica del Vehículo
                    </h2>
                    <p class="text-sm text-indigo-600 dark:text-indigo-400 font-bold mt-1 uppercase tracking-wider">
                        Gestión de Flota Vehicular — SIAFNIN
                    </p>
                </div>
            </div>
        </template>

        <div class="py-6 bg-gray-50 dark:bg-gray-900 min-h-screen">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-2xl">
                    <div class="p-6">
                        <!-- Botones de Acción Superiores -->
                        <div class="flex flex-wrap items-center justify-between gap-3 mb-8 pb-6 border-b border-gray-100 dark:border-gray-700">
                            <div class="flex items-center gap-2">
                                <Link :href="route('vehiculos.index')" class="inline-flex items-center justify-center px-6 h-11 bg-white dark:bg-gray-800 text-gray-500 hover:text-gray-900 dark:hover:text-gray-200 rounded-xl text-xs font-black transition-all border border-gray-200 dark:border-gray-700 hover:border-gray-400 min-w-[180px]">
                                    <ArrowLeft class="w-4 h-4 mr-2" /> VOLVER AL LISTADO
                                </Link>
                                <a :href="route('vehiculos.print', props.vehiculo.id)" target="_blank" class="inline-flex items-center justify-center px-6 h-11 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-black transition-all shadow-sm min-w-[160px]">
                                    <Printer class="w-4 h-4 mr-2" /> FICHA TÉCNICA
                                </a>
                            </div>

                            <div class="flex flex-wrap items-center gap-2">
                                <Link :href="route('vehiculos.index', { edit_id: props.vehiculo.id })" class="inline-flex items-center justify-center px-6 h-11 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-black transition-all shadow-sm min-w-[160px]">
                                    <Pencil class="w-4 h-4 mr-1.5" /> EDITAR
                                </Link>
                                <Link :href="route('activos.historial', { activo: props.vehiculo.activo_fijo_id })" class="inline-flex items-center justify-center px-6 h-11 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-black transition-all shadow-sm min-w-[160px]">
                                    <History class="w-4 h-4 mr-1.5" /> HISTORIAL
                                </Link>
                            </div>
                        </div>
                        
                        <!-- Cabecera del Vehículo -->
                        <div class="flex flex-col lg:flex-row gap-8 mb-10">
                            <div v-if="vehiculo.imagen_url" class="w-full lg:w-80 shrink-0">
                                <img :src="vehiculo.imagen_url" class="w-full h-72 object-cover rounded-2xl shadow-xl border-4 border-white dark:border-gray-700" alt="Foto del vehículo">
                            </div>
                            
                            <div class="flex-1">
                                <div class="flex flex-wrap items-start justify-between gap-4 mb-4">
                                    <div class="flex-1 min-w-[300px]">
                                        <div class="flex items-center gap-3 mb-2">
                                            <div class="px-3 py-1 bg-yellow-400 text-black font-black font-mono text-xl rounded border-2 border-black shadow-sm">
                                                {{ vehiculo.placa }}
                                            </div>
                                            <span class="inline-flex items-center px-2 py-0.5 bg-emerald-50 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300 text-xs font-black uppercase tracking-wider rounded-md border border-emerald-100 dark:border-emerald-800/50">
                                                {{ vehiculo.estado }}
                                            </span>
                                        </div>
                                        <h1 class="text-2xl font-black text-gray-900 dark:text-white mb-2 leading-tight uppercase tracking-tight">
                                            {{ vehiculo.nombre }}
                                        </h1>
                                    </div>
                                    <div class="bg-gray-50 dark:bg-gray-800 border-2 border-dashed border-gray-200 dark:border-gray-700 p-3 rounded-xl text-center">
                                        <p class="text-[11px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-1.5">CÓDIGO INVENTARIO</p>
                                        <p class="text-xl font-black text-indigo-600 dark:text-indigo-400 font-mono tracking-tight">{{ vehiculo.codigo_inventario || 'PENDIENTE' }}</p>
                                    </div>
                                </div>

                                <!-- Datos Técnicos Rápidos -->
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                                    <div class="bg-blue-50/50 dark:bg-blue-900/10 p-3.5 rounded-2xl border border-blue-50 dark:border-blue-900/20 flex flex-col items-center justify-center text-center">
                                        <div class="flex items-center gap-2 mb-1.5">
                                            <Calendar class="w-3.5 h-3.5 text-blue-500" />
                                            <p class="text-xs font-black text-blue-500 uppercase tracking-tighter">Año</p>
                                        </div>
                                        <p class="text-base font-black text-gray-900 dark:text-white">{{ vehiculo.anio }}</p>
                                    </div>
                                    <div class="bg-purple-50/50 dark:bg-purple-900/10 p-3.5 rounded-2xl border border-purple-50 dark:border-purple-900/20 flex flex-col items-center justify-center text-center">
                                        <div class="flex items-center gap-2 mb-1.5">
                                            <Gauge class="w-3.5 h-3.5 text-purple-500" />
                                            <p class="text-xs font-black text-purple-500 uppercase tracking-tighter">Kilometraje</p>
                                        </div>
                                        <p class="text-base font-black text-gray-900 dark:text-white leading-none">{{ vehiculo.kilometraje }} <span class="text-[10px] font-bold text-purple-400">km</span></p>
                                    </div>
                                    <div class="bg-orange-50/50 dark:bg-orange-900/10 p-3.5 rounded-2xl border border-orange-50 dark:border-orange-900/20 flex flex-col items-center justify-center text-center">
                                        <div class="flex items-center gap-2 mb-1.5">
                                            <Fuel class="w-3.5 h-3.5 text-orange-500" />
                                            <p class="text-xs font-black text-orange-500 uppercase tracking-tighter">Combustible</p>
                                        </div>
                                        <p class="text-base font-black text-gray-900 dark:text-white truncate w-full">{{ vehiculo.tipo_combustible }}</p>
                                    </div>
                                    <div class="bg-teal-50/50 dark:bg-teal-900/10 p-3.5 rounded-2xl border border-teal-50 dark:border-teal-900/20 flex flex-col items-center justify-center text-center">
                                        <div class="flex items-center gap-2 mb-1.5">
                                            <Users class="w-3.5 h-3.5 text-teal-500" />
                                            <p class="text-xs font-black text-teal-500 uppercase tracking-tighter">Pasajeros</p>
                                        </div>
                                        <p class="text-base font-black text-gray-900 dark:text-white">{{ vehiculo.capacidad_pasajeros }}</p>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 p-5 bg-white dark:bg-gray-800/50 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm">
                                    <div class="flex items-start gap-3">
                                        <div class="p-2 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                            <MapPin class="w-4 h-4 text-gray-400" />
                                        </div>
                                        <div>
                                            <p class="text-[11px] font-black text-gray-400 uppercase tracking-wider mb-0.5">Ubicación / Depto</p>
                                            <p class="text-sm font-black text-gray-900 dark:text-white leading-tight uppercase">{{ vehiculo.ubicacion }}</p>
                                            <p class="text-[10px] font-medium text-gray-500 truncate">{{ vehiculo.departamento }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-start gap-3">
                                        <div class="p-2 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                            <User class="w-4 h-4 text-gray-400" />
                                        </div>
                                        <div>
                                            <p class="text-[11px] font-black text-gray-400 uppercase tracking-wider mb-0.5">Responsable / Conductor</p>
                                            <p class="text-sm font-black text-gray-900 dark:text-white leading-tight uppercase">{{ vehiculo.responsable || 'No asignado' }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-start gap-3 pt-2 border-t border-gray-50 dark:border-gray-700/50">
                                        <div class="p-2 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                            <Tag class="w-4 h-4 text-gray-400" />
                                        </div>
                                        <div>
                                            <p class="text-[11px] font-black text-gray-400 uppercase tracking-wider mb-0.5">Marca / Modelo</p>
                                            <p class="text-sm font-black text-gray-900 dark:text-white leading-tight uppercase">{{ vehiculo.marca || '-' }}</p>
                                            <p class="text-xs font-medium text-gray-500 uppercase">{{ vehiculo.modelo || '-' }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-start gap-3 pt-2 border-t border-gray-50 dark:border-gray-700/50">
                                        <div class="p-2 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                            <Milestone class="w-4 h-4 text-gray-400" />
                                        </div>
                                        <div>
                                            <p class="text-[11px] font-black text-gray-400 uppercase tracking-wider mb-0.5">Chasis / Motor</p>
                                            <p class="text-xs font-black text-gray-900 dark:text-white leading-tight font-mono tracking-tighter">{{ vehiculo.numero_serie || 'N/A' }}</p>
                                            <p v-if="vehiculo.numero_motor" class="text-xs font-medium text-gray-500 uppercase">MOTOR: {{ vehiculo.numero_motor }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-6">
                            <div class="flex items-center gap-2 mb-6 border-b border-gray-100 dark:border-gray-700 pb-2">
                                <span class="p-2 bg-indigo-50 dark:bg-indigo-900/30 rounded-lg">
                                    <Wallet class="w-5 h-5 text-indigo-600" />
                                </span>
                                <h3 class="text-lg font-black text-gray-900 dark:text-white uppercase tracking-tight">Detalle Financiero y Depreciación</h3>
                            </div>
                            
                            <!-- Cabecera Financiera (3 Columnas) -->
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                                <div class="bg-gray-50/80 dark:bg-gray-800/80 p-5 rounded-2xl border border-gray-100 dark:border-gray-700 flex flex-col justify-center items-center h-full min-h-[110px]">
                                    <p class="text-xs font-bold text-gray-400 uppercase tracking-[0.2em] mb-2">Fecha Adquisición</p>
                                    <p class="text-2xl font-black text-gray-900 dark:text-white">{{ vehiculo.fecha_adquisicion }}</p>
                                    <div class="mt-2 px-3 py-1 bg-amber-100/50 dark:bg-amber-900/20 text-amber-600 rounded-lg text-xs font-black tracking-tighter uppercase">
                                        Hace {{ vehiculo.meses_depreciados }} meses
                                    </div>
                                </div>
                                <div class="bg-gray-50/80 dark:bg-gray-800/80 p-5 rounded-2xl border border-gray-100 dark:border-gray-700 flex flex-col justify-center items-center h-full min-h-[110px]">
                                    <p class="text-xs font-bold text-gray-400 uppercase tracking-[0.2em] mb-2">Valor Adquisición</p>
                                    <p class="text-2xl font-black text-gray-900 dark:text-white">{{ formatCurrency(vehiculo.valor_adquisicion) }}</p>
                                </div>
                                <div class="bg-gray-50/80 dark:bg-gray-800/80 p-5 rounded-2xl border border-gray-100 dark:border-gray-700 flex flex-col justify-center items-center h-full min-h-[110px]">
                                    <p class="text-xs font-bold text-gray-400 uppercase tracking-[0.2em] mb-2">Valor Residual</p>
                                    <p class="text-2xl font-black text-gray-900 dark:text-white">{{ formatCurrency(vehiculo.valor_residual) }}</p>
                                </div>
                            </div>

                            <!-- Grilla de Depreciación (4 Columnas Equilibradas) -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                                <div class="p-5 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl flex flex-col justify-center items-center h-full">
                                    <p class="text-[11px] font-black text-gray-400 uppercase tracking-[0.2em] mb-3">Vida Útil</p>
                                    <p class="text-2xl font-black text-gray-900 dark:text-white leading-none">{{ vehiculo.vida_util_anios }} <span class="text-xs text-gray-400 uppercase font-black">Años</span></p>
                                </div>
                                <div class="p-5 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl flex flex-col justify-center items-center h-full">
                                    <p class="text-[11px] font-black text-gray-400 uppercase tracking-[0.2em] mb-3">Depreciación Anual</p>
                                    <p class="text-2xl font-black text-indigo-600 dark:text-indigo-400 leading-none">{{ formatCurrency(vehiculo.depreciacion_anual) }}</p>
                                </div>
                                <div class="p-5 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl flex flex-col justify-center items-center h-full">
                                    <p class="text-[11px] font-black text-gray-400 uppercase tracking-[0.2em] mb-3">Depreciación Mensual</p>
                                    <p class="text-2xl font-black text-indigo-600 dark:text-indigo-400 leading-none">{{ formatCurrency(vehiculo.depreciacion_mensual) }}</p>
                                </div>
                                <div class="p-5 bg-indigo-600 dark:bg-indigo-700 rounded-2xl shadow-xl shadow-indigo-100 dark:shadow-none flex flex-col justify-center items-center h-full text-white relative group overflow-hidden">
                                    <!-- Fondo Decorativo -->
                                    <div class="absolute -right-4 -top-4 bg-white/10 w-16 h-16 rounded-full group-hover:scale-150 transition-transform duration-700"></div>
                                    
                                    <p class="text-[11px] font-bold uppercase tracking-[0.2em] mb-3 text-indigo-100">Valor Neto Actual</p>
                                    <p class="text-2xl font-black leading-none mb-3 z-10">{{ formatCurrency(vehiculo.valor_neto) }}</p>
                                    
                                    <div class="pt-2.5 border-t border-white/20 w-full text-center z-10">
                                        <div class="flex items-center justify-center gap-1.5">
                                            <span class="text-[11px] font-bold text-indigo-100 uppercase">Dep. Acum:</span>
                                            <span class="bg-white/20 backdrop-blur-md px-2 py-0.5 rounded text-xs font-black text-white">{{ formatCurrency(vehiculo.depreciacion_acumulada) }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

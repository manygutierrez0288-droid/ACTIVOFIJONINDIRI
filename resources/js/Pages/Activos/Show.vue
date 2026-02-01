<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import AuditHistory from '@/Components/AuditHistory.vue';
import { Head, Link } from '@inertiajs/vue3';
import { 
    Calendar, DollarSign, Clock, TrendingDown, ShieldCheck, 
    MapPin, User, Tag, Package, ArrowLeft, Pencil, History, 
    Move, Wrench, Trash2, Printer, Info, LineChart, Wallet 
} from 'lucide-vue-next';

const props = defineProps({
    activo: Object,
    auditorias: Array,
    mantenimientos: Array,
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
                        <!-- Botones de Acción Superiores -->
                        <div class="flex flex-col gap-8 mb-10 pb-8 border-b border-gray-100 dark:border-gray-700">
                            <!-- Fila 1: Navegación e Impresión -->
                            <div class="flex flex-wrap items-center justify-between gap-4">
                                <Link :href="route('activos.index')" class="inline-flex items-center justify-center px-6 h-11 bg-white dark:bg-gray-800 text-gray-500 hover:text-gray-900 dark:hover:text-gray-200 rounded-xl text-xs font-black transition-all border border-gray-200 dark:border-gray-700 hover:border-gray-400 hover:shadow-md min-w-[180px]">
                                    <ArrowLeft class="w-4 h-4 mr-2" /> VOLVER AL LISTADO
                                </Link>

                                <div class="flex items-center gap-3">
                                    <a :href="route('activos.print', { activoFijo: props.activo.id })" target="_blank" class="inline-flex items-center justify-center px-6 h-11 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-black transition-all shadow-lg shadow-indigo-100 dark:shadow-none hover:-translate-y-0.5 active:translate-y-0 min-w-[160px]">
                                        <Printer class="w-4 h-4 mr-2" /> FICHA TÉCNICA
                                    </a>
                                    <a :href="route('activos.label', { activoFijo: props.activo.id })" target="_blank" class="inline-flex items-center justify-center px-6 h-11 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-black transition-all shadow-lg shadow-emerald-100 dark:shadow-none hover:-translate-y-0.5 active:translate-y-0 min-w-[160px]">
                                        <Tag class="w-4 h-4 mr-2" /> ETIQUETA QR
                                    </a>
                                </div>
                            </div>

                            <!-- Fila 2: Gestión y Operaciones -->
                            <div class="bg-gray-50/50 dark:bg-gray-900/30 p-4 rounded-2xl border border-gray-100 dark:border-gray-800">
                                <div class="flex flex-wrap items-center gap-3">
                                    <div class="flex items-center gap-2 mr-4 border-r border-gray-200 dark:border-gray-700 pr-4">
                                        <div class="p-2 bg-gray-200/50 dark:bg-gray-800 rounded-lg">
                                            <Info class="w-4 h-4 text-gray-400" />
                                        </div>
                                        <span class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-[0.2em]">Acciones de Gestión</span>
                                    </div>
                                    
                                    <div class="flex flex-wrap items-center gap-2">
                                        <Link :href="route('activos.index', { edit_id: props.activo.id })" class="inline-flex items-center justify-center px-6 h-11 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-black transition-all shadow-sm min-w-[160px]">
                                            <Pencil class="w-4 h-4 mr-2" /> EDITAR DATOS
                                        </Link>
                                        
                                        <Link :href="route('activos.historial', { activo: props.activo.id })" class="inline-flex items-center justify-center px-6 h-11 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-black transition-all shadow-sm min-w-[160px]">
                                            <History class="w-4 h-4 mr-2" /> AUDITORÍA
                                        </Link>

                                        <Link :href="route('movimientos.create', { activo: props.activo.id })" class="inline-flex items-center justify-center px-6 h-11 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-black transition-all shadow-sm min-w-[160px]">
                                            <Move class="w-4 h-4 mr-2" /> TRASLADAR
                                        </Link>
                                        
                                        <Link :href="route('mantenimientos.create', { activo: props.activo.id })" class="inline-flex items-center justify-center px-6 h-11 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-black transition-all shadow-sm min-w-[160px]">
                                            <Wrench class="w-4 h-4 mr-2" /> MANTENIMIENTO
                                        </Link>

                                        <template v-if="activo.estado_id === 6 && activo.baja_id">
                                            <a :href="route('bajas.acta-baja', { baja: activo.baja_id })" target="_blank" class="inline-flex items-center justify-center px-6 h-11 bg-red-700 hover:bg-black text-white rounded-xl text-xs font-black transition-all min-w-[160px]">
                                                <Printer class="w-4 h-4 mr-2" /> ACTA DE BAJA
                                            </a>
                                        </template>
                                        <Link v-else :href="route('bajas.create', { activo: props.activo.id })" class="inline-flex items-center justify-center px-6 h-11 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-xs font-black transition-all shadow-sm min-w-[160px]">
                                            <Trash2 class="w-4 h-4 mr-2" /> SOLICITAR BAJA
                                        </Link>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Cabecera del Activo -->
                        <div class="flex flex-col lg:flex-row gap-8 mb-10">
                            <div v-if="activo.imagen_url" class="w-full lg:w-72 shrink-0">
                                <img :src="activo.imagen_url" class="w-full h-64 object-cover rounded-2xl shadow-xl border-4 border-white dark:border-gray-700" alt="Foto del activo">
                            </div>
                            
                            <div class="flex-1">
                                    <div class="flex-1 min-w-[300px]">
                                        <h1 class="text-2xl font-black text-gray-900 dark:text-white mb-2 leading-tight uppercase tracking-tight">
                                            {{ activo.nombre }}
                                        </h1>
                                        <div class="flex items-center gap-2">
                                            <span class="inline-flex items-center px-2 py-0.5 bg-indigo-50 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-300 text-xs font-black uppercase tracking-wider rounded-md border border-indigo-100 dark:border-indigo-800/50">
                                                {{ activo.categoria }}
                                            </span>
                                            <span class="inline-flex items-center px-2 py-0.5 bg-emerald-50 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300 text-xs font-black uppercase tracking-wider rounded-md border border-emerald-100 dark:border-emerald-800/50">
                                                {{ activo.estado_nombre }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="bg-gray-50 dark:bg-gray-800 border-2 border-dashed border-gray-200 dark:border-gray-700 p-3 rounded-xl text-center">
                                        <p class="text-[11px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-1.5">CÓDIGO INVENTARIO</p>
                                        <p class="text-xl font-black text-indigo-600 dark:text-indigo-400 font-mono tracking-tight">{{ activo.codigo_inventario || 'PENDIENTE' }}</p>
                                    </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 p-5 bg-white dark:bg-gray-800/50 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm">
                                    <div class="flex items-start gap-3">
                                        <div class="p-2 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                            <MapPin class="w-4 h-4 text-gray-400" />
                                        </div>
                                        <div>
                                            <p class="text-[11px] font-black text-gray-400 uppercase tracking-wider mb-0.5">Ubicación / Depto</p>
                                            <p class="text-sm font-black text-gray-900 dark:text-white leading-tight uppercase">{{ activo.ubicacion }}</p>
                                            <p class="text-xs font-medium text-gray-500 truncate">{{ activo.departamento }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-start gap-3">
                                        <div class="p-2 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                            <User class="w-4 h-4 text-gray-400" />
                                        </div>
                                        <div>
                                            <p class="text-[11px] font-black text-gray-400 uppercase tracking-wider mb-0.5">Responsable / Encargado</p>
                                            <p class="text-sm font-black text-gray-900 dark:text-white leading-tight uppercase">{{ activo.responsable || 'No asignado' }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-start gap-3 pt-2 border-t border-gray-50 dark:border-gray-700/50">
                                        <div class="p-2 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                            <Tag class="w-4 h-4 text-gray-400" />
                                        </div>
                                        <div>
                                            <p class="text-[11px] font-black text-gray-400 uppercase tracking-wider mb-0.5">Marca / Modelo</p>
                                            <p class="text-sm font-black text-gray-900 dark:text-white leading-tight uppercase">{{ activo.marca || '-' }}</p>
                                            <p class="text-xs font-medium text-gray-500 uppercase">{{ activo.modelo || '-' }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-start gap-3 pt-2 border-t border-gray-50 dark:border-gray-700/50">
                                        <div class="p-2 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                            <Info class="w-4 h-4 text-gray-400" />
                                        </div>
                                        <div>
                                            <p class="text-[11px] font-black text-gray-400 uppercase tracking-wider mb-0.5">ID del Sistema</p>
                                            <p class="text-sm font-black text-gray-900 dark:text-white leading-tight font-mono tracking-tighter">#{{ activo.id }}</p>
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
                                    <p class="text-2xl font-black text-gray-900 dark:text-white">{{ activo.fecha_adquisicion }}</p>
                                    <div class="mt-2 px-3 py-1 bg-amber-100/50 dark:bg-amber-900/20 text-amber-600 rounded-lg text-xs font-black tracking-tighter uppercase">
                                        Hace {{ activo.meses_depreciados }} meses
                                    </div>
                                </div>
                                <div class="bg-gray-50/80 dark:bg-gray-800/80 p-5 rounded-2xl border border-gray-100 dark:border-gray-700 flex flex-col justify-center items-center h-full min-h-[110px]">
                                    <p class="text-xs font-bold text-gray-400 uppercase tracking-[0.2em] mb-2">Valor Adquisición</p>
                                    <p class="text-2xl font-black text-gray-900 dark:text-white">{{ formatCurrency(activo.valor_adquisicion) }}</p>
                                </div>
                                <div class="bg-gray-50/80 dark:bg-gray-800/80 p-5 rounded-2xl border border-gray-100 dark:border-gray-700 flex flex-col justify-center items-center h-full min-h-[110px]">
                                    <p class="text-xs font-bold text-gray-400 uppercase tracking-[0.2em] mb-2">Valor Residual</p>
                                    <p class="text-2xl font-black text-gray-900 dark:text-white">{{ formatCurrency(activo.valor_residual) }}</p>
                                </div>
                            </div>

                            <!-- Grilla de Depreciación (4 Columnas Equilibradas) -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                                <div class="p-5 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl flex flex-col justify-center items-center h-full">
                                    <p class="text-[11px] font-black text-gray-400 uppercase tracking-[0.2em] mb-3">Vida Útil</p>
                                    <p class="text-2xl font-black text-gray-900 dark:text-white leading-none">{{ activo.vida_util_anios }} <span class="text-xs text-gray-400 uppercase font-black">Años</span></p>
                                </div>
                                <div class="p-5 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl flex flex-col justify-center items-center h-full">
                                    <p class="text-[11px] font-black text-gray-400 uppercase tracking-[0.2em] mb-3">Depreciación Anual</p>
                                    <p class="text-2xl font-black text-indigo-600 dark:text-indigo-400 leading-none">{{ formatCurrency(activo.depreciacion_anual) }}</p>
                                </div>
                                <div class="p-5 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl flex flex-col justify-center items-center h-full">
                                    <p class="text-[11px] font-black text-gray-400 uppercase tracking-[0.2em] mb-3">Depreciación Mensual</p>
                                    <p class="text-2xl font-black text-indigo-600 dark:text-indigo-400 leading-none">{{ formatCurrency(activo.depreciacion_mensual) }}</p>
                                </div>
                                <div class="p-5 bg-indigo-600 dark:bg-indigo-700 rounded-2xl shadow-xl shadow-indigo-100 dark:shadow-none flex flex-col justify-center items-center h-full text-white relative group overflow-hidden">
                                    <!-- Fondo Decorativo -->
                                    <div class="absolute -right-4 -top-4 bg-white/10 w-16 h-16 rounded-full group-hover:scale-150 transition-transform duration-700"></div>
                                    
                                    <p class="text-[11px] font-bold uppercase tracking-[0.2em] mb-3 text-indigo-100">Valor Neto Actual</p>
                                    <p class="text-2xl font-black leading-none mb-3 z-10">{{ formatCurrency(activo.valor_neto) }}</p>
                                    
                                    <div class="pt-2.5 border-t border-white/20 w-full text-center z-10">
                                        <div class="flex items-center justify-center gap-1.5">
                                            <span class="text-[11px] font-bold text-indigo-100 uppercase">Dep. Acum:</span>
                                            <span class="bg-white/20 backdrop-blur-md px-2 py-0.5 rounded text-xs font-black text-white">{{ formatCurrency(activo.depreciacion_acumulada) }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Maintenance History Section -->
                        <div v-if="mantenimientos && mantenimientos.length > 0" class="mt-10">
                            <div class="flex items-center gap-2 mb-6 border-b border-gray-100 dark:border-gray-700 pb-2">
                                <span class="p-2 bg-emerald-50 dark:bg-emerald-900/30 rounded-lg">
                                    <Wrench class="w-5 h-5 text-emerald-600" />
                                </span>
                                <h3 class="text-lg font-black text-gray-900 dark:text-white uppercase tracking-tight">Historial de Mantenimientos</h3>
                            </div>

                            <div class="overflow-x-auto bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm">
                                <table class="w-full text-left">
                                    <thead>
                                        <tr class="bg-gray-50 dark:bg-gray-900/50">
                                            <th class="px-4 py-3 text-[10px] font-black text-gray-400 uppercase tracking-widest">Fecha</th>
                                            <th class="px-4 py-3 text-[10px] font-black text-gray-400 uppercase tracking-widest">Descripción</th>
                                            <th class="px-4 py-3 text-[10px] font-black text-gray-400 uppercase tracking-widest">Técnico / Proveedor</th>
                                            <th class="px-4 py-3 text-[10px] font-black text-gray-400 uppercase tracking-widest text-right">Costo</th>
                                            <th class="px-4 py-3 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-50 dark:divide-gray-800">
                                        <tr v-for="mant in mantenimientos" :key="mant.id" class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                                            <td class="px-4 py-4 text-xs font-bold text-gray-900 dark:text-gray-100 italic">{{ new Date(mant.fecha).toLocaleDateString() }}</td>
                                            <td class="px-4 py-4 text-xs text-gray-600 dark:text-gray-400 max-w-xs truncate">{{ mant.descripcion }}</td>
                                            <td class="px-4 py-4 text-xs text-gray-600 dark:text-gray-400">
                                                <div class="font-bold text-gray-800 dark:text-gray-200 uppercase">{{ mant.tecnico?.nombre || '-' }}</div>
                                                <div class="text-[10px] opacity-70">{{ mant.proveedor?.nombre || 'Taller Interno' }}</div>
                                            </td>
                                            <td class="px-4 py-4 text-xs font-bold text-right text-gray-900 dark:text-white">{{ formatCurrency(mant.costo) }}</td>
                                            <td class="px-4 py-4 text-center">
                                                <a :href="route('mantenimientos.print', { mantenimiento: mant.id })" target="_blank" class="inline-flex items-center p-2 text-indigo-600 hover:bg-indigo-50 dark:hover:bg-indigo-900/30 rounded-lg transition-colors" title="Imprimir Ficha">
                                                    <Printer class="w-4 h-4" />
                                                </a>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Audit History Section -->
                        <hr class="border-gray-200 dark:border-gray-700 my-10">
                        <AuditHistory :auditorias="auditorias" />
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

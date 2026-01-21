<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { computed } from 'vue';
import { 
    LayoutDashboard, 
    DollarSign, 
    TrendingDown, 
    Wallet, 
    Activity,
    Package,
    AlertCircle,
    BarChart3,
    PieChart
} from 'lucide-vue-next';

const props = defineProps({
    stats: Object,
    activos_por_estado: Array,
    activos_por_categoria: Array,
    evolucion_mensual: Array,
    activos_recientes: Array,
});

// Formatear moneda
const formatCurrency = (value) => {
    return new Intl.NumberFormat('es-NI', {
        style: 'currency',
        currency: 'NIO',
    }).format(value);
};

// Configuración Gráfico de Categorías (Barra Horizontal)
const categoryChartOptions = computed(() => ({
    chart: {
        type: 'bar',
        toolbar: { show: false },
        fontFamily: 'Inter, sans-serif'
    },
    plotOptions: {
        bar: {
            borderRadius: 4,
            horizontal: true,
            barHeight: '70%',
            distributed: true
        }
    },
    dataLabels: { enabled: false },
    xaxis: {
        categories: (props.activos_por_categoria || []).map(item => item.categoria),
        labels: {
            style: { colors: '#6B7280', fontSize: '12px' }
        }
    },
    yaxis: {
        labels: {
            style: { colors: '#6B7280', fontSize: '12px' }
        }
    },
    colors: ['#3F83F8', '#0E9F6E', '#F05252', '#FF5A1F', '#7E3AF2', '#6366F1', '#EC4899', '#14B8A6'],
    grid: { borderColor: '#F3F4F6', strokeDashArray: 4 },
    tooltip: { theme: 'light' },
    legend: { show: false }
}));

const categoryChartSeries = computed(() => [{
    name: 'Total Activos',
    data: (props.activos_por_categoria || []).map(item => item.total)
}]);

// Configuración Gráfico de Evolución (Línea Suave)
const evolutionChartOptions = computed(() => ({
    chart: {
        type: 'area',
        toolbar: { show: false },
        fontFamily: 'Inter, sans-serif',
        zoom: { enabled: false }
    },
    dataLabels: { enabled: false },
    stroke: { curve: 'smooth', width: 3 },
    xaxis: {
        categories: (props.evolucion_mensual || []).map(item => item.mes),
        labels: { style: { colors: '#6B7280', fontSize: '12px' } },
        axisBorder: { show: false },
        axisTicks: { show: false }
    },
    yaxis: {
        labels: {
            formatter: (val) => new Intl.NumberFormat('es-NI', { notation: "compact", compactDisplay: "short" }).format(val),
            style: { colors: '#6B7280', fontSize: '12px' }
        }
    },
    fill: {
        type: 'gradient',
        gradient: {
            shadeIntensity: 1,
            opacityFrom: 0.7,
            opacityTo: 0.2,
            stops: [0, 90, 100]
        }
    },
    colors: ['#1C64F2'],
    grid: { borderColor: '#F3F4F6', strokeDashArray: 4 },
    tooltip: { theme: 'light', y: { formatter: (val) => formatCurrency(val) } }
}));

const evolutionChartSeries = computed(() => [{
    name: 'Valor Adquisición',
    data: (props.evolucion_mensual || []).map(item => item.total)
}]);

// Gráfico de Estado (Donut/Pastel)
const statusChartOptions = computed(() => ({
    chart: { 
        type: 'donut',
        fontFamily: 'Inter, sans-serif'
    },
    labels: (props.activos_por_estado || []).map(item => item.estado),
    colors: ['#0E9F6E', '#E3A008', '#F05252', '#3F83F8', '#9CA3AF'],
    plotOptions: {
        pie: {
            donut: {
                size: '65%',
                labels: {
                    show: true,
                    name: { show: true },
                    value: { show: true, fontSize: '20px', fontWeight: 600 }
                }
            }
        }
    },
    legend: { position: 'bottom', fontFamily: 'Inter, sans-serif' },
    dataLabels: { enabled: false },
    tooltip: { theme: 'light' }
}));

const statusChartSeries = computed(() => (props.activos_por_estado || []).map(item => item.total));

</script>

<script>
import VueApexCharts from 'vue3-apexcharts';

export default {
    components: {
        apexchart: VueApexCharts,
    },
};
</script>


<template>
    <Head title="Dashboard - Activos Fijos" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h2 class="text-3xl font-extrabold tracking-tight text-gray-900 dark:text-white">
                        Bienvenido, {{ $page.props.auth.user.name }}
                    </h2>
                    <p class="text-lg text-indigo-600 dark:text-indigo-400 font-medium mt-1">
                        Sistema de Activo Fijo — Alcaldía de Nindirí
                    </p>
                </div>
                <div class="hidden md:block">
                    <div class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm">
                        <span class="relative flex h-3 w-3 mr-3">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
                        </span>
                        <span class="text-sm font-medium text-gray-600 dark:text-gray-300">Sistema en línea</span>
                    </div>
                </div>
            </div>
        </template>

        <div class="bg-gray-50 dark:bg-gray-900 px-4 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-full space-y-4">
                
                <!-- KPI Cards Section -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <!-- Total Activos -->
                    <div class="bg-white dark:bg-gray-800 p-4 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 hover:shadow-md transition-shadow duration-300 relative overflow-hidden group">
                        <div class="flex justify-between items-start z-10 relative">
                            <div>
                                <p class="text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Total Activos</p>
                                <h3 class="text-2xl font-bold text-gray-900 dark:text-white">{{ stats.total_activos }}</h3>
                            </div>
                            <div class="p-2 bg-blue-50 dark:bg-blue-900/30 rounded-lg text-blue-600 dark:text-blue-400">
                                <Package class="w-6 h-6" />
                            </div>
                        </div>
                        <div class="absolute -bottom-4 -right-4 w-24 h-24 bg-blue-50 dark:bg-blue-900/10 rounded-full opacity-50 group-hover:scale-110 transition-transform duration-500"></div>
                    </div>

                    <!-- Valor Total -->
                    <div class="bg-white dark:bg-gray-800 p-4 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 hover:shadow-md transition-shadow duration-300 relative overflow-hidden group">
                        <div class="flex justify-between items-start z-10 relative">
                            <div>
                                <p class="text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Valor Total</p>
                                <h3 class="text-2xl font-bold text-gray-900 dark:text-white">{{ formatCurrency(stats.valor_total) }}</h3>
                            </div>
                            <div class="p-2 bg-green-50 dark:bg-green-900/30 rounded-lg text-green-600 dark:text-green-400">
                                <DollarSign class="w-6 h-6" />
                            </div>
                        </div>
                        <div class="absolute -bottom-4 -right-4 w-24 h-24 bg-green-50 dark:bg-green-900/10 rounded-full opacity-50 group-hover:scale-110 transition-transform duration-500"></div>
                    </div>

                    <!-- Depreciación Acumulada -->
                    <div class="bg-white dark:bg-gray-800 p-4 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 hover:shadow-md transition-shadow duration-300 relative overflow-hidden group">
                        <div class="flex justify-between items-start z-10 relative">
                            <div>
                                <p class="text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Depreciación Acum.</p>
                                <h3 class="text-2xl font-bold text-red-600 dark:text-red-400">{{ formatCurrency(stats.depreciacion_total) }}</h3>
                            </div>
                            <div class="p-2 bg-red-50 dark:bg-red-900/30 rounded-lg text-red-600 dark:text-red-400">
                                <TrendingDown class="w-6 h-6" />
                            </div>
                        </div>
                        <div class="absolute -bottom-4 -right-4 w-24 h-24 bg-red-50 dark:bg-red-900/10 rounded-full opacity-50 group-hover:scale-110 transition-transform duration-500"></div>
                    </div>

                    <!-- Valor Neto -->
                    <div class="bg-white dark:bg-gray-800 p-4 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 hover:shadow-md transition-shadow duration-300 relative overflow-hidden group">
                        <div class="flex justify-between items-start z-10 relative">
                            <div>
                                <p class="text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Valor Neto Actual</p>
                                <h3 class="text-2xl font-bold text-indigo-600 dark:text-indigo-400">{{ formatCurrency(stats.valor_neto) }}</h3>
                            </div>
                            <div class="p-2 bg-indigo-50 dark:bg-indigo-900/30 rounded-lg text-indigo-600 dark:text-indigo-400">
                                <Wallet class="w-6 h-6" />
                            </div>
                        </div>
                        <div class="absolute -bottom-4 -right-4 w-24 h-24 bg-indigo-50 dark:bg-indigo-900/10 rounded-full opacity-50 group-hover:scale-110 transition-transform duration-500"></div>
                    </div>
                </div>

                <!-- Charts Row -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Category Chart -->
                    <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-white flex items-center gap-2">
                                <BarChart3 class="w-5 h-5 text-indigo-600" />
                                Por Categoría
                            </h3>
                        </div>
                        <div class="h-64">
                            <apexchart 
                                v-if="activos_por_categoria.length > 0"
                                width="100%" 
                                height="100%" 
                                :options="categoryChartOptions" 
                                :series="categoryChartSeries" 
                            />
                            <div v-else class="flex items-center justify-center h-full text-gray-500 dark:text-gray-400">
                                <div class="text-center">
                                    <AlertCircle class="w-8 h-8 mx-auto mb-2 text-gray-300" />
                                    <p class="text-sm">No hay datos disponibles</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Status Chart (Gráfico de Pastel) -->
                    <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-white flex items-center gap-2">
                                <PieChart class="w-5 h-5 text-green-600" />
                                Estado de Activos
                            </h3>
                        </div>
                        <div class="h-64 flex items-center justify-center">
                            <apexchart 
                                v-if="activos_por_estado.length > 0"
                                width="100%" 
                                height="100%" 
                                type="donut"
                                :options="statusChartOptions" 
                                :series="statusChartSeries" 
                            />
                            <div v-else class="text-center text-gray-500 dark:text-gray-400">
                                <AlertCircle class="w-8 h-8 mx-auto mb-2 text-gray-300" />
                                <p class="text-sm">No hay datos disponibles</p>
                            </div>
                        </div>
                    </div>

                     <!-- Evolution Chart -->
                     <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-white flex items-center gap-2">
                                <Activity class="w-5 h-5 text-blue-600" />
                                Adquisiciones
                            </h3>
                            <span class="text-xs font-medium px-2 py-1 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 rounded">Últimos 12 meses</span>
                        </div>
                        <div class="h-64">
                            <apexchart 
                                v-if="evolucion_mensual.length > 0"
                                width="100%" 
                                height="100%" 
                                :options="evolutionChartOptions" 
                                :series="evolutionChartSeries" 
                            />
                            <div v-else class="flex items-center justify-center h-full text-gray-500 dark:text-gray-400">
                                <div class="text-center">
                                    <AlertCircle class="w-8 h-8 mx-auto mb-2 text-gray-300" />
                                    <p class="text-sm">No hay datos disponibles</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Assets Table -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                    <div class="p-4 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center">
                        <h3 class="text-base font-bold text-gray-800 dark:text-white flex items-center gap-2">
                            <Activity class="w-5 h-5 text-gray-500" />
                            Activos Registrados Recientemente
                        </h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <thead class="bg-gray-50 dark:bg-gray-700/50 text-xs uppercase text-gray-500 dark:text-gray-400 font-semibold">
                                <tr>
                                    <th class="px-6 py-2">Activo</th>
                                    <th class="px-6 py-2">Categoría</th>
                                    <th class="px-6 py-2">Estado</th>
                                    <th class="px-6 py-2 text-right">Valor Adq.</th>
                                    <th class="px-6 py-2 text-right">Valor Neto</th>
                                    <th class="px-6 py-2 text-center">Fecha</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                <tr v-for="activo in activos_recientes" :key="activo.id" class="hover:bg-gray-50/50 dark:hover:bg-gray-700/50 transition-colors">
                                    <td class="px-6 py-2 font-medium text-gray-900 dark:text-white">
                                        {{ activo.nombre }}
                                    </td>
                                    <td class="px-6 py-4 text-gray-500 dark:text-gray-400">
                                        <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300">
                                            {{ activo.categoria }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span v-if="activo.estado?.toLowerCase().includes('buen')" class="flex items-center gap-1.5 text-green-600 dark:text-green-400 text-xs font-medium">
                                            <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> {{ activo.estado }}
                                        </span>
                                        <span v-else-if="activo.estado?.toLowerCase().includes('regular')" class="flex items-center gap-1.5 text-yellow-600 dark:text-yellow-400 text-xs font-medium">
                                            <span class="w-1.5 h-1.5 rounded-full bg-yellow-500"></span> {{ activo.estado }}
                                        </span>
                                        <span v-else-if="activo.estado?.toLowerCase().includes('mal')" class="flex items-center gap-1.5 text-red-600 dark:text-red-400 text-xs font-medium">
                                            <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span> {{ activo.estado }}
                                        </span>
                                        <span v-else class="flex items-center gap-1.5 text-gray-600 dark:text-gray-400 text-xs font-medium">
                                            <span class="w-1.5 h-1.5 rounded-full bg-gray-500"></span> {{ activo.estado }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right text-gray-900 dark:text-white font-medium">
                                        {{ formatCurrency(activo.valor_adquisicion) }}
                                    </td>
                                    <td class="px-6 py-4 text-right text-emerald-600 dark:text-emerald-400 font-bold">
                                        {{ formatCurrency(activo.valor_neto) }}
                                    </td>
                                    <td class="px-6 py-4 text-center text-gray-500 text-xs">
                                        {{ activo.fecha_adquisicion }}
                                    </td>
                                </tr>
                                <tr v-if="activos_recientes.length === 0">
                                    <td colspan="6" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400 flex flex-col items-center justify-center">
                                        <AlertCircle class="w-8 h-8 mb-2 text-gray-300" />
                                        <p>No hay activos registrados recientemente.</p>
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

<style scoped>
.custom-scrollbar::-webkit-scrollbar {
    width: 6px;
}
.custom-scrollbar::-webkit-scrollbar-track {
    background: transparent;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    background: rgba(156, 163, 175, 0.3);
    border-radius: 10px;
}
.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: rgba(156, 163, 175, 0.5);
}
</style>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { ref, onMounted, watch, computed } from 'vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import { 
    FileSpreadsheet, 
    FileText, 
    Printer, 
    BarChart3, 
    ClipboardList, 
    Layers, 
    Activity, 
    BookOpen, 
    CalendarDays,
    Search,
    Filter,
    ArrowLeft,
    Eye,
    ChevronRight,
    Loader2,
    Calendar,
    Download,
    ArrowRightCircle,
    CheckCircle2,
    ShieldCheck,
    Wrench
} from 'lucide-vue-next';

const props = defineProps({
    categorias: Array,
    departamentos: Array,
    ubicaciones: Array,
});

// Navigation State
const viewMode = ref('menu'); // 'menu' | 'report'
const selectedReport = ref(null);

const reportOptions = [
    { id: 'inventario', label: 'Inventario General', desc: 'Listado completo y detallado de todos los activos fijos registrados.', icon: ClipboardList, color: 'text-blue-500', bg: 'bg-blue-50 dark:bg-blue-900/20' },
    { id: 'depreciacion', label: 'Cálculo Depreciación', desc: 'Análisis del valor actual, desgaste acumulado y proyección contable.', icon: BarChart3, color: 'text-emerald-500', bg: 'bg-emerald-50 dark:bg-emerald-900/20' },
    { id: 'categoria', label: 'Por Categoría', desc: 'Clasificación organizada de bienes según su naturaleza técnica.', icon: Layers, color: 'text-indigo-500', bg: 'bg-indigo-50 dark:bg-indigo-900/20' },
    { id: 'acciones', label: 'Movimientos y Gestiones', desc: 'Auditoría completa de movimientos, cambios y gestiones realizadas.', icon: Activity, color: 'text-amber-500', bg: 'bg-amber-50 dark:bg-amber-900/20' },
    { id: 'mantenimientos', label: 'Mantenimientos', desc: 'Reporte detallado de mantenimientos preventivos y correctivos realizados.', icon: Wrench, color: 'text-emerald-600', bg: 'bg-emerald-50 dark:bg-emerald-900/20' },
    { id: 'auditoria_sistema', label: 'Auditoría de Cambios', desc: 'Registro técnico detallado de modificaciones en campos y datos por usuario.', icon: ShieldCheck, color: 'text-rose-500', bg: 'bg-rose-50 dark:bg-rose-900/20' },
    { id: 'libro_activos', label: 'Libro de Activos', desc: 'Registro pormenorizado para cumplimiento de normativas externas.', icon: BookOpen, color: 'text-purple-500', bg: 'bg-purple-50 dark:bg-purple-900/20' },
    { id: 'resumen_mensual', label: 'Resumen Mensual', desc: 'Consolidado de altas y bajas durante el ciclo de operación actual.', icon: CalendarDays, color: 'text-cyan-500', bg: 'bg-cyan-50 dark:bg-cyan-900/20' },
    { id: 'vehiculos', label: 'Flota Vehicular', desc: 'Detalle técnico y operativo de todos los vehículos asignados.', icon: Printer, color: 'text-orange-500', bg: 'bg-orange-50 dark:bg-orange-900/20' },
];

const selectReport = (report) => {
    selectedReport.value = report;
    reportType.value = report.id;
    viewMode.value = 'report';
    reportData.value = [];
    columns.value = [];
    fetchReport(); // Carga automática al seleccionar
};

const backToMenu = () => {
    viewMode.value = 'menu';
    selectedReport.value = null;
};

// Report Logic
const reportType = ref('');
const reportData = ref([]);
const loading = ref(false);

const filters = ref({
    fecha_inicio: '',
    fecha_fin: '',
    categoria_id: '',
    departamento_id: '',
    ubicacion_id: '',
});

const columns = ref([]);

const fetchReport = async () => {
    loading.value = true;
    reportData.value = [];
    columns.value = [];
    try {
        const params = new URLSearchParams({
            type: reportType.value,
            ...filters.value
        });
        const response = await fetch(route('reportes.data') + '?' + params.toString());
        const data = await response.json();
        reportData.value = data;
        
        if (data.length > 0) {
            columns.value = Object.keys(data[0]);
        }
    } catch (error) {
        console.error("Error fetching report:", error);
    } finally {
        loading.value = false;
    }
};

const exportExcel = () => {
    const params = new URLSearchParams({
        report_type: reportType.value,
        format: 'excel',
        ...filters.value
    });
    window.location.href = route('reportes.export') + '?' + params.toString();
};

const exportPdf = () => {
    const params = new URLSearchParams({
        report_type: reportType.value,
        format: 'pdf',
        ...filters.value
    });
    window.open(route('reportes.export') + '?' + params.toString(), '_blank');
};

const documentNumber = ref('---');

const printReport = async () => {
    try {
        const response = await axios.post(route('reportes.generar-consecutivo'), { 
            type: reportType.value 
        });
        if (response.data && response.data.codigo) {
            documentNumber.value = response.data.codigo;
        }
        setTimeout(() => window.print(), 500);
    } catch (error) {
        window.print();
    }
};

const columnTotals = computed(() => {
    if (reportData.value.length === 0 || columns.value.length === 0) return {};
    
    // Define columns that should be totaled (whitelisted)
    const numericColumns = ['Valor', 'Valor Adquisicion', 'Valor Residual', 'Depreciacion Acumulada', 'Valor Neto', 'Total Valor', 'Deprec. Mes', 'Deprec. Acum.', 'Valor Bruto'];

    const totals = {};
    columns.value.forEach(col => {
        // Only calculate total if column is in usage whitelist
        if (!numericColumns.includes(col)) {
            totals[col] = '';
            return;
        }

        let sum = 0;
        let isNumeric = true;
        reportData.value.forEach(row => {
            const cleanValue = String(row[col]).replace(/[^0-9.-]/g, '');
            if (cleanValue && !isNaN(parseFloat(cleanValue))) sum += parseFloat(cleanValue);
            else if (row[col] && String(row[col]).trim() !== '') isNumeric = false;
        });
        totals[col] = isNumeric && sum !== 0 ? sum.toLocaleString('es-NI', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) : '';
    });
    return totals;
});
</script>

<template>
    <Head title="Reportes" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center gap-4">
                <button v-if="viewMode === 'report'" @click="backToMenu" class="p-2 hover:bg-white dark:hover:bg-gray-800 rounded-full transition-colors text-gray-500">
                    <ArrowLeft class="w-5 h-5" />
                </button>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Reportes</h2>
            </div>
        </template>

        <div class="py-6 min-h-[80vh]">
            <!-- SELECTION PORTAL -->
            <div v-if="viewMode === 'menu'" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 animate-in fade-in slide-in-from-bottom-4 duration-500">
                <div class="col-span-full mb-4">
                    <h3 class="text-2xl font-black text-gray-900 dark:text-white tracking-tight">Seleccione el módulo de reporte</h3>
                    <p class="text-sm text-gray-500 font-medium">Elija los datos que desea consultar para proceder con los filtros.</p>
                </div>

                <div 
                    v-for="opt in reportOptions" 
                    :key="opt.id"
                    @click="selectReport(opt)"
                    class="group relative bg-white dark:bg-gray-800 rounded-[32px] p-8 border border-gray-100 dark:border-gray-700 shadow-sm hover:shadow-2xl hover:shadow-indigo-500/10 hover:border-indigo-200 dark:hover:border-indigo-900/50 transition-all duration-300 cursor-pointer overflow-hidden"
                >
                    <div class="flex flex-col h-full">
                        <div :class="['w-16 h-16 rounded-2xl flex items-center justify-center mb-6 transition-transform duration-500 group-hover:scale-110 group-hover:rotate-3 shadow-lg shadow-black/5', opt.bg]">
                            <component :is="opt.icon" :class="['w-8 h-8', opt.color]" />
                        </div>
                        
                        <h4 class="text-lg font-black text-gray-900 dark:text-white uppercase tracking-tighter mb-2">{{ opt.label }}</h4>
                        <p class="text-sm text-gray-500 dark:text-gray-400 font-medium leading-relaxed grow">
                            {{ opt.desc }}
                        </p>

                        <div class="mt-8 flex items-center justify-between">
                            <span class="text-[10px] font-bold uppercase tracking-widest text-indigo-500 flex items-center gap-1.5 group-hover:gap-3 transition-all">
                                Configurar Reporte <ArrowRightCircle class="w-4 h-4" />
                            </span>
                        </div>
                    </div>

                    <!-- Decorative Background element -->
                    <div class="absolute -right-8 -top-8 w-32 h-32 bg-indigo-500/5 rounded-full blur-3xl group-hover:bg-indigo-500/20 transition-colors duration-500"></div>
                </div>
            </div>

            <!-- FOCUSED REPORT VIEW -->
            <div v-else class="space-y-6 animate-in fade-in slide-in-from-right-4 duration-500">
                <!-- Report Header & Actions -->
                <div class="bg-white dark:bg-gray-800 rounded-3xl border border-gray-100 dark:border-gray-700 p-6 flex flex-col md:flex-row items-center justify-between gap-6 shadow-sm">
                    <div class="flex items-center gap-6">
                        <div :class="['w-16 h-16 rounded-2xl flex items-center justify-center shadow-inner', selectedReport.bg]">
                            <component :is="selectedReport.icon" :class="['w-8 h-8', selectedReport.color]" />
                        </div>
                        <div>
                            <div class="flex items-center gap-2 mb-1">
                                <span class="px-2 py-0.5 bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 text-[10px] font-bold uppercase tracking-widest rounded-full">Módulo Activo</span>
                                <span class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">{{ new Date().toLocaleDateString('es-ES', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' }) }}</span>
                            </div>
                            <h3 class="text-2xl font-black text-gray-900 dark:text-white uppercase tracking-tighter">{{ selectedReport.label }}</h3>
                        </div>
                    </div>

                    <div class="flex flex-wrap items-center justify-center gap-3">
                        <SecondaryButton @click="exportExcel" class="rounded-xl grow justify-center border-emerald-100 dark:border-emerald-900/30 text-emerald-600 dark:text-emerald-400 hover:bg-emerald-50 dark:hover:bg-emerald-900/20 py-3 font-bold text-xs">
                           <FileSpreadsheet class="w-4 h-4 mr-2" /> EXCEL
                        </SecondaryButton>
                        <SecondaryButton @click="exportPdf" class="rounded-xl grow justify-center border-red-100 dark:border-red-900/30 text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 py-3 font-bold text-xs">
                           <FileText class="w-4 h-4 mr-2" /> PDF
                        </SecondaryButton>
                        <button @click="printReport" class="flex items-center gap-2 px-6 py-3 bg-gray-900 dark:bg-white text-white dark:text-gray-900 rounded-xl hover:bg-gray-800 dark:hover:bg-gray-100 transition-all font-black text-xs uppercase tracking-widest">
                            <Printer class="w-4 h-4" /> Imprimir Oficial
                        </button>
                    </div>
                </div>

                <div class="grid grid-cols-1 xl:grid-cols-4 gap-6 items-start">
                    <!-- Filters Column -->
                    <div class="xl:col-span-1 space-y-6">
                        <div class="bg-white dark:bg-gray-800 rounded-3xl border border-gray-100 dark:border-gray-700 p-6 shadow-sm">
                            <div class="flex items-center gap-2 mb-6">
                                <Filter class="w-4 h-4 text-indigo-500" />
                                <h3 class="text-[10px] font-black text-gray-900 dark:text-white uppercase tracking-[0.2em]">Filtros de Datos</h3>
                            </div>

                            <div class="space-y-6">
                                <div class="space-y-2">
                                    <InputLabel value="Categoría de Activo" class="text-[10px] font-bold uppercase text-gray-400" />
                                    <select v-model="filters.categoria_id" class="w-full border-gray-50 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-xs font-bold p-3">
                                        <option value="">Todas las categorías</option>
                                        <option v-for="cat in categorias" :key="cat.id" :value="cat.id">{{ cat.nombre }}</option>
                                    </select>
                                </div>

                                <div class="space-y-2">
                                    <InputLabel value="Departamento / Área" class="text-[10px] font-bold uppercase text-gray-400" />
                                    <select v-model="filters.departamento_id" class="w-full border-gray-50 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-xs font-bold p-3">
                                        <option value="">Todos los departamentos</option>
                                        <option v-for="dep in departamentos" :key="dep.id" :value="dep.id">{{ dep.nombre }}</option>
                                    </select>
                                </div>

                                <div class="space-y-3">
                                    <InputLabel value="Rango Temporal" class="text-[10px] font-bold uppercase text-gray-400" />
                                    <div class="relative">
                                        <Calendar class="absolute left-4 top-3.5 w-3.5 h-3.5 text-gray-400" />
                                        <input type="date" v-model="filters.fecha_inicio" class="w-full pl-10 pr-4 py-3 bg-gray-50 dark:bg-gray-900 border-none rounded-xl text-xs font-bold focus:ring-2 focus:ring-indigo-500" />
                                    </div>
                                    <div class="relative">
                                        <Calendar class="absolute left-4 top-3.5 w-3.5 h-3.5 text-gray-400" />
                                        <input type="date" v-model="filters.fecha_fin" class="w-full pl-10 pr-4 py-3 bg-gray-50 dark:bg-gray-900 border-none rounded-xl text-xs font-bold focus:ring-2 focus:ring-indigo-500" />
                                    </div>
                                </div>

                                <PrimaryButton @click="fetchReport" :disabled="loading" class="w-full py-4 rounded-xl justify-center font-black text-xs uppercase tracking-widest shadow-lg shadow-indigo-500/20">
                                    <Loader2 v-if="loading" class="w-4 h-4 animate-spin mr-2" />
                                    <Search v-else class="w-4 h-4 mr-2" />
                                    Generar Resultados
                                </PrimaryButton>
                            </div>
                        </div>

                        <button @click="backToMenu" class="w-full p-4 flex items-center justify-center gap-2 text-[10px] font-black uppercase text-gray-400 hover:text-indigo-600 transition-all">
                            <ArrowLeft class="w-3.5 h-3.5" /> Volver al menú principal
                        </button>
                    </div>

                    <!-- Table Column -->
                    <div class="xl:col-span-3">
                        <div class="bg-white dark:bg-gray-800 rounded-[32px] border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden min-h-[500px] flex flex-col">
                            <!-- Table Header Info -->
                            <div class="px-8 py-4 border-b border-gray-50 dark:border-gray-700 flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-2 h-2 rounded-full animate-pulse bg-emerald-500"></div>
                                    <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Previsualización de sistema</span>
                                </div>
                                <div v-if="reportData.length > 0" class="flex gap-2">
                                    <span class="px-3 py-1 bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 rounded-full text-[10px] font-black">
                                        {{ reportData.length }} ENCONTRADOS
                                    </span>
                                </div>
                            </div>

                            <!-- Content Wrapper -->
                            <div class="flex-1 overflow-x-auto">
                                <!-- Loading -->
                                <div v-if="loading" class="h-full flex flex-col items-center justify-center py-20 gap-4">
                                    <div class="w-16 h-16 border-[6px] border-indigo-50 border-t-indigo-500 rounded-full animate-spin"></div>
                                    <p class="text-xs font-black text-gray-400 uppercase tracking-[0.3em]">Cargando Datos</p>
                                </div>

                                <!-- Empty -->
                                <div v-else-if="reportData.length === 0" class="h-full flex flex-col items-center justify-center py-20 gap-6">
                                    <div class="p-6 bg-gray-50 dark:bg-gray-900 rounded-full">
                                        <Search class="w-12 h-12 text-gray-200" />
                                    </div>
                                    <div class="text-center">
                                        <p class="text-sm font-black text-gray-400 uppercase tracking-widest">Sin información disponible</p>
                                        <p class="text-[10px] text-gray-400 mt-1 uppercase font-bold">Configure los filtros y pulse "Generar Resultados"</p>
                                    </div>
                                </div>

                                <!-- Table -->
                                <div v-else class="p-6">
                                    <table class="w-full text-left border-separate border-spacing-0">
                                        <thead>
                                            <tr>
                                                <th v-for="col in columns" :key="col" class="px-4 py-4 text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest border-b border-gray-50 dark:border-gray-700 whitespace-nowrap">
                                                    {{ col.replace(/_/g, ' ') }}
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-50 dark:divide-gray-800">
                                            <tr v-for="(row, idx) in reportData" :key="idx" class="hover:bg-indigo-50/20 dark:hover:bg-indigo-900/5 transition-colors group">
                                                <td v-for="col in columns" :key="col" class="px-4 py-4 text-xs text-gray-700 dark:text-gray-300 font-bold group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">
                                                    {{ row[col] }}
                                                </td>
                                            </tr>
                                        </tbody>
                                        <tfoot class="bg-gray-50/30 dark:bg-gray-900/30 backdrop-blur-xl">
                                            <tr>
                                                <td v-for="(col, index) in columns" :key="col" class="px-4 py-6 text-xs font-black text-gray-900 dark:text-white border-t-2 border-indigo-50 dark:border-indigo-900/20">
                                                    <span v-if="index === 0" class="flex items-center gap-2">
                                                        <ChevronRight class="w-4 h-4 text-indigo-500" /> TOTALES
                                                    </span>
                                                    <span v-else class="text-indigo-600 dark:text-indigo-400">{{ columnTotals[col] }}</span>
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- PRINT VERSION -->
            <div class="hidden print:block bg-white p-0">
                <div class="header-container grid grid-cols-3 items-center mb-8 border-b-4 border-indigo-900 pb-6">
                    <div class="flex justify-start">
                        <img src="/images/logo_alcaldia_nindiri.png" alt="Logo" class="h-24 w-auto object-contain" />
                    </div>
                    <div class="text-center col-span-1">
                        <h1 class="text-3xl font-black uppercase tracking-tight text-indigo-900">Alcaldía Municipal de Nindirí</h1>
                        <h2 class="text-lg font-bold uppercase text-gray-500 mt-1">Departamento de Control de Activo Fijo</h2>
                        <h3 class="text-sm font-black text-indigo-700 mt-2 uppercase tracking-widest">{{ selectedReport?.label }}</h3>
                    </div>
                    <div class="flex justify-end">
                        <!-- Espacio para balancear el centrado -->
                    </div>
                </div>

                <div class="meta-section w-full mb-8 flex justify-between items-center text-[10px] font-black text-gray-500 uppercase tracking-widest border-b border-gray-100 pb-4">
                    <span>Emitido el: {{ new Date().toLocaleString('es-ES', { day: '2-digit', month: '2-digit', year: 'numeric', hour: '2-digit', minute: '2-digit' }) }}</span>
                    <span>Documento No: <span class="font-mono text-sm border-b-2 border-indigo-900 text-indigo-900 px-2">{{ documentNumber }}</span></span>
                    <span>Usuario: {{ $page.props.auth.user.name }}</span>
                </div>

                <table class="w-full border-collapse border border-gray-200 text-[8pt]">
                    <thead>
                        <tr class="bg-indigo-900 text-white">
                            <th v-for="col in columns" :key="col" class="border border-indigo-900 p-3 uppercase text-left font-black tracking-tight">{{ col.replace(/_/g, ' ') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(row, idx) in reportData" :key="idx" :class="idx % 2 === 0 ? 'bg-white' : 'bg-gray-50'">
                            <td v-for="col in columns" :key="col" class="border border-gray-200 p-3 font-semibold text-gray-700">{{ row[col] }}</td>
                        </tr>
                    </tbody>
                    <tfoot class="bg-gray-100 font-black">
                        <tr class="border-t-2 border-indigo-900">
                            <td v-for="(col, index) in columns" :key="col" class="border border-gray-200 p-4 text-sm text-indigo-900">
                                <span v-if="index === 0" class="uppercase">Totales del Reporte:</span>
                                <span v-else>{{ columnTotals[col] }}</span>
                            </td>
                        </tr>
                    </tfoot>
                </table>

                <div class="mt-24 flex justify-around items-end text-center print:mt-32">
                    <div class="w-1/3">
                        <div class="border-t-2 border-indigo-900 mx-4 pt-3 text-[10px] font-black uppercase text-indigo-900">Firma de Responsable Administrativo</div>
                    </div>
                    <div class="w-1/3">
                        <div class="border-t-2 border-indigo-900 mx-4 pt-3 text-[10px] font-black uppercase text-indigo-900">Sello de Departamento / Auditoría</div>
                    </div>
                </div>

                <div class="print-footer fixed bottom-4 w-full text-center text-[8px] font-bold text-gray-400 uppercase tracking-widest">
                    SIAFNIN - Sistema Integrado de Activos Fijos | Generado Oficialmente por la Alcaldía Municipal de Nindirí
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style>
@media print {
    /* Eliminar encabezados y pies de página del navegador */
    @page { 
        margin: 0; 
        size: letter landscape; 
    }

    /* Ocultar elementos innecesarios del sistema */
    #app > div > aside,
    #app > div > main > header,
    nav,
    .print\:hidden {
        display: none !important;
    }

    body {
        background: white !important;
        color: black !important;
        margin: 0 !important;
        padding: 0 !important;
    }

    /* Contenedor principal de impresión con margen físico simulado */
    .print\:block {
        display: block !important;
        padding: 1.5cm !important;
        width: 100% !important;
    }

    /* Estilos técnicos de la tabla para impresión - Ajuste de escala */
    table {
        width: 100% !important;
        border-collapse: collapse !important;
        font-family: Arial, sans-serif !important;
        table-layout: auto !important;
        font-size: 8pt !important; /* Fuente más pequeña para evitar desbordes */
    }

    th, td {
        border: 1pt solid black !important;
        padding: 4pt 2pt !important; /* Menos padding lateral */
        text-align: left !important;
        word-wrap: break-word !important;
        overflow-wrap: break-word !important;
        white-space: normal !important; /* Permitir saltos de línea en celdas */
    }

    th {
        background-color: #f3f4f6 !important;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }

    thead {
        display: table-header-group !important;
    }

    tr {
        page-break-inside: avoid !important;
    }

    /* Asegurar visibilidad del logo oficial */
    img {
        max-height: 3.5cm !important;
        width: auto !important;
        margin-bottom: 0.5cm !important;
    }
}

/* Custom Scrollbar for the table */
.overflow-x-auto::-webkit-scrollbar {
  height: 8px;
}
.overflow-x-auto::-webkit-scrollbar-track {
  background: transparent;
}
.overflow-x-auto::-webkit-scrollbar-thumb {
  background: #e2e8f0;
  border-radius: 10px;
}
.dark .overflow-x-auto::-webkit-scrollbar-thumb {
  background: #334155;
}
</style>

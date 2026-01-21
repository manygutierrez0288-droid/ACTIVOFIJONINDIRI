<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { 
    History, 
    ArrowLeft, 
    User, 
    Calendar,
    FileText,
    PlusCircle,
    Edit2,
    Trash2,
    ArrowRight,
    Activity,
    Info,
    Clock,
    CheckCircle2
} from 'lucide-vue-next';
import { computed } from 'vue';

const props = defineProps({
    activo: Object,
    auditorias: Object,
});

const formatDate = (dateString) => {
    if (!dateString) return '';
    const date = new Date(dateString);
    return new Intl.DateTimeFormat('es-NI', {
        day: '2-digit',
        month: 'short',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
        hour12: true
    }).format(date);
};

const getActionIcon = (accion) => {
    switch (accion) {
        case 'created': return PlusCircle;
        case 'updated': return Edit2;
        case 'deleted': return Trash2;
        default: return FileText;
    }
};

const getActionColor = (accion) => {
    switch (accion) {
        case 'created': return 'text-emerald-600 bg-emerald-50 dark:bg-emerald-900/20 border-emerald-100 dark:border-emerald-800/30';
        case 'updated': return 'text-indigo-600 bg-indigo-50 dark:bg-indigo-900/20 border-indigo-100 dark:border-indigo-800/30';
        case 'deleted': return 'text-rose-600 bg-rose-50 dark:bg-rose-900/20 border-rose-100 dark:border-rose-800/30';
        default: return 'text-blue-600 bg-blue-50 dark:bg-blue-900/20 border-blue-100 dark:border-blue-800/30';
    }
};

const getActionLabel = (accion) => {
    switch (accion) {
        case 'created': return 'Creación de Registro';
        case 'updated': return 'Actualización de Datos';
        case 'deleted': return 'Eliminación de Registro';
        default: return 'Acción Registrada';
    }
};

const fieldLabels = {
    nombre: 'Nombre del Activo',
    categoria_id: 'Categoría',
    departamento_id: 'Departamento',
    ubicacion_id: 'Ubicación',
    marca_id: 'Marca',
    modelo_id: 'Modelo',
    color_id: 'Color',
    fuente_id: 'Fuente de Financiamiento',
    proveedor_id: 'Proveedor',
    responsable_id: 'Personal Responsable',
    estado_id: 'Estado del Activo',
    fecha_adquisicion: 'Fecha de Adquisición',
    valor_adquisicion: 'Valor de Adquisición',
    vida_util_anios: 'Vida Útil (Años)',
    valor_residual: 'Valor Residual',
    depreciacion_acumulada: 'Depreciación Acumulada',
    numero_serie: 'Número de Serie',
    codigo_inventario: 'Código de Inventario',
    imagen_url: 'Imagen',
    valor_residual_automatico: 'Cálculo Residual Automático'
};

const formatFieldName = (key) => fieldLabels[key] || key;

const formatValue = (value) => {
    if (value === null || value === undefined || value === '') return 'N/A';
    if (typeof value === 'boolean') return value ? 'Sí' : 'No';
    if (typeof value === 'string' && value.match(/^\d{4}-\d{2}-\d{2}T/)) {
        return formatDate(value);
    }
    return value;
};

// Helper to safely parse JSON if value is a string
const parseAuditValues = (value) => {
    if (typeof value === 'string') {
        try {
            return JSON.parse(value);
        } catch (e) {
            console.error("Failed to parse audit values:", e);
            return {};
        }
    }
    return value || {};
};

// Helper to get only changed fields for "updated" action
const getChanges = (audit) => {
    const valoresNuevos = parseAuditValues(audit.valores_nuevos);
    const valoresAnteriores = parseAuditValues(audit.valores_anteriores);

    if (audit.accion === 'created') {
         return Object.keys(valoresNuevos).map(key => ({
            key,
            old: null,
            new: valoresNuevos[key]
        }));
    }

    if (audit.accion !== 'updated') return [];

    const changes = [];
    for (const key in valoresNuevos) {
        if (JSON.stringify(valoresNuevos[key]) !== JSON.stringify(valoresAnteriores[key])) {
             changes.push({
                key,
                old: valoresAnteriores[key],
                new: valoresNuevos[key]
            });
        }
    }
    return changes;
};

// Summary stats
const stats = computed(() => {
    const data = props.auditorias.data || [];
    return {
        total: props.auditorias.total || data.length,
        updates: data.filter(a => a.accion === 'updated').length,
        lastDate: data.length > 0 ? formatDate(data[0].fecha_hora) : 'N/A',
        activeEditor: data.length > 0 ? (data[0].usuario?.name || 'Sistema') : 'N/A'
    };
});
</script>

<template>
    <Head title="Historial de Auditoría" />
    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <Link :href="route('activos.show', activo.id)" 
                        class="p-2.5 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 hover:bg-gray-50 transition-all duration-200 group">
                        <ArrowLeft class="w-5 h-5 text-gray-500 group-hover:-translate-x-1 transition-transform" />
                    </Link>
                    <div class="flex flex-col">
                        <h2 class="font-black text-2xl text-gray-900 dark:text-white leading-tight tracking-tight">Historial de Auditoría</h2>
                        <div class="flex items-center gap-2 mt-1">
                            <span class="px-2 py-0.5 bg-indigo-100 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-300 text-[10px] font-bold uppercase rounded-md tracking-wider">
                                {{ activo.codigo_inventario }}
                            </span>
                            <span class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate max-w-[300px]">
                                {{ activo.nombre }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Action Button if needed -->
                <div class="hidden md:block">
                     <div class="flex items-center gap-2 text-xs font-semibold text-gray-400 bg-gray-100 dark:bg-gray-800/50 px-4 py-2 rounded-full border border-gray-200/50 dark:border-gray-700/50">
                        <Activity class="w-3.5 h-3.5" />
                        Registro de Actividad en Tiempo Real
                    </div>
                </div>
            </div>
        </template>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">
            
            <!-- Stats Dashboard -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-white dark:bg-gray-800 p-5 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 flex items-center gap-4 hover:shadow-md transition-shadow">
                    <div class="p-3 bg-blue-50 dark:bg-blue-900/20 rounded-2xl text-blue-600 dark:text-blue-400">
                        <History class="w-6 h-6" />
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Total Cambios</p>
                        <p class="text-xl font-black text-gray-900 dark:text-white">{{ stats.total }}</p>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 p-5 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 flex items-center gap-4 hover:shadow-md transition-shadow">
                    <div class="p-3 bg-indigo-50 dark:bg-indigo-900/20 rounded-2xl text-indigo-600 dark:text-indigo-400">
                        <Edit2 class="w-6 h-6" />
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Actualizaciones</p>
                        <p class="text-xl font-black text-gray-900 dark:text-white">{{ stats.updates }}</p>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 p-5 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 flex items-center gap-4 hover:shadow-md transition-shadow">
                    <div class="p-3 bg-emerald-50 dark:bg-emerald-900/20 rounded-2xl text-emerald-600 dark:text-emerald-400">
                        <User class="w-6 h-6" />
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Último Editor</p>
                        <p class="text-xl font-black text-gray-900 dark:text-white truncate max-w-[120px]" :title="stats.activeEditor">{{ stats.activeEditor }}</p>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 p-5 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 flex items-center gap-4 hover:shadow-md transition-shadow">
                    <div class="p-3 bg-amber-50 dark:bg-amber-900/20 rounded-2xl text-amber-600 dark:text-amber-400">
                        <Clock class="w-6 h-6" />
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Última Actividad</p>
                        <p class="text-xl font-black text-gray-900 dark:text-white">{{ stats.lastDate.split(',')[0] }}</p>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="relative pl-8 md:pl-0">
                <!-- Vertical Progress Line (Desktop) -->
                <div class="hidden md:block absolute left-1/2 top-0 bottom-0 w-1 bg-gradient-to-b from-indigo-50 via-gray-100 to-transparent dark:from-indigo-900/20 dark:via-gray-800/40 transform -translate-x-1/2"></div>
                
                <div v-if="auditorias.data.length === 0" class="bg-white dark:bg-gray-800 p-16 rounded-[2.5rem] shadow-sm border border-gray-100 dark:border-gray-700 text-center flex flex-col items-center">
                    <div class="w-20 h-20 bg-gray-50 dark:bg-gray-900 rounded-full flex items-center justify-center mb-6">
                        <History class="w-10 h-10 text-gray-300 dark:text-gray-600" />
                    </div>
                    <h3 class="text-xl font-black text-gray-900 dark:text-white mb-2">Sin historial registrado</h3>
                    <p class="text-gray-500 dark:text-gray-400 max-w-sm">No se han registrado cambios significativos para este activo en la base de datos.</p>
                </div>

                <div v-else class="space-y-12">
                    <div v-for="(audit, index) in auditorias.data" :key="audit.id" 
                        class="relative flex flex-col md:flex-row items-center gap-8 md:gap-0 animate-in fade-in slide-in-from-bottom-4 duration-500"
                        :style="`animation-delay: ${index * 100}ms`">
                        
                        <!-- Timeline Dot (Desktop) -->
                        <div class="hidden md:flex absolute left-1/2 top-10 w-10 h-10 rounded-full border-4 border-white dark:border-gray-900 shadow-xl items-center justify-center z-10 transform -translate-x-1/2 transition-transform hover:scale-110"
                            :class="getActionColor(audit.accion)">
                            <component :is="getActionIcon(audit.accion)" class="w-5 h-5" />
                        </div>

                        <!-- Content Card -->
                        <div class="w-full md:w-[45%] group" :class="index % 2 === 0 ? 'md:mr-auto' : 'md:ml-auto'">
                            <div class="bg-white dark:bg-gray-800 rounded-[2rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)] dark:shadow-[0_8px_30px_rgb(0,0,0,0.1)] border border-gray-100/50 dark:border-gray-700/50 overflow-hidden hover:border-indigo-200 dark:hover:border-indigo-900/50 transition-all duration-300 transform group-hover:-translate-y-1">
                                <!-- Card Header -->
                                <div class="px-6 py-5 flex items-center justify-between border-b border-gray-50 dark:border-gray-700/50"
                                     :class="index % 2 === 0 ? 'flex-row' : 'flex-row'">
                                    <div class="flex items-center gap-3">
                                        <div class="md:hidden p-2 rounded-xl" :class="getActionColor(audit.accion)">
                                            <component :is="getActionIcon(audit.accion)" class="w-4 h-4" />
                                        </div>
                                        <div>
                                            <span class="text-[10px] font-black uppercase tracking-[0.2em] opacity-40 mb-1 block">Acción</span>
                                            <h4 class="font-black text-gray-900 dark:text-white text-sm tracking-tight">{{ getActionLabel(audit.accion) }}</h4>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <span class="text-[10px] font-black uppercase tracking-[0.2em] opacity-40 mb-1 block">Fecha y Hora</span>
                                        <div class="flex items-center gap-2 text-xs font-bold text-gray-500 bg-gray-50 dark:bg-gray-900/50 px-2.5 py-1 rounded-lg border border-gray-100 dark:border-gray-700">
                                            <Clock class="w-3 h-3 text-indigo-500" />
                                            {{ formatDate(audit.fecha_hora).split(',')[1].trim() }}
                                        </div>
                                    </div>
                                </div>

                                <!-- Card Body -->
                                <div class="p-6">
                                    <!-- User Badge -->
                                    <div class="flex items-center gap-2 mb-6 p-2 bg-gray-50/80 dark:bg-gray-900/40 rounded-2xl border border-gray-100/30 dark:border-gray-700/30">
                                        <div class="w-8 h-8 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-500 flex items-center justify-center text-white shadow-lg overflow-hidden">
                                            <span class="text-xs font-black">{{ (audit.usuario?.name || 'S').charAt(0) }}</span>
                                        </div>
                                        <div class="flex flex-col">
                                            <span class="text-[9px] font-bold text-gray-400 uppercase tracking-wider">Realizado por</span>
                                            <span class="text-xs font-bold text-gray-700 dark:text-gray-300">{{ audit.usuario?.name || 'Sistema' }}</span>
                                        </div>
                                        <div class="ml-auto flex items-center gap-1.5 px-2 py-0.5 bg-white dark:bg-gray-800 rounded-lg text-[10px] text-gray-400 font-bold border border-gray-100 dark:border-gray-700">
                                            <Calendar class="w-2.5 h-2.5" />
                                            {{ formatDate(audit.fecha_hora).split(',')[0] }}
                                        </div>
                                    </div>

                                    <!-- Changes Display -->
                                    <div v-if="audit.accion === 'created'" class="space-y-4">
                                        <div class="bg-emerald-50/50 dark:bg-emerald-900/10 p-4 rounded-2xl border border-emerald-100/50 dark:border-emerald-800/20 flex items-start gap-3">
                                            <div class="p-1.5 bg-emerald-100 dark:bg-emerald-800/30 rounded-lg text-emerald-600">
                                                <CheckCircle2 class="w-4 h-4" />
                                            </div>
                                            <p class="text-xs font-medium text-emerald-700 dark:text-emerald-400">Primer registro del activo con sus especificaciones iniciales.</p>
                                        </div>
                                        <div class="grid gap-2">
                                            <div v-for="(val, key) in parseAuditValues(audit.valores_nuevos)" :key="key" 
                                                class="flex items-center justify-between p-3 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-900/50 transition-colors border-b border-gray-50 dark:border-gray-700 last:border-0 group/field">
                                                <span class="text-xs font-semibold text-gray-400 group-hover/field:text-gray-600 transition-colors">{{ formatFieldName(key) }}</span>
                                                <span class="text-xs font-black text-gray-900 dark:text-white">{{ formatValue(val) }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div v-else-if="audit.accion === 'deleted'" class="text-center py-6">
                                        <div class="w-12 h-12 bg-rose-50 dark:bg-rose-900/20 rounded-full flex items-center justify-center mx-auto mb-4 text-rose-500">
                                            <Trash2 class="w-6 h-6" />
                                        </div>
                                        <p class="text-sm font-bold text-rose-600 dark:text-rose-400">Activo dado de baja del sistema</p>
                                    </div>

                                    <div v-else class="space-y-4">
                                        <div v-if="getChanges(audit).length === 0" class="flex items-center gap-3 p-4 bg-gray-50 dark:bg-gray-900/40 rounded-2xl border border-dashed border-gray-200 dark:border-gray-700">
                                            <Info class="w-4 h-4 text-gray-400" />
                                            <span class="text-xs text-gray-400 italic font-medium">No se detectaron cambios en los campos monitoreados.</span>
                                        </div>
                                        <div v-else class="grid gap-4">
                                            <div v-for="change in getChanges(audit)" :key="change.key" 
                                                class="p-4 rounded-2xl bg-gray-50/50 dark:bg-gray-900/20 border border-gray-100/50 dark:border-gray-700/50 group/row">
                                                <div class="flex items-center justify-between mb-3">
                                                    <span class="text-[10px] font-black text-indigo-600 dark:text-indigo-400 uppercase tracking-widest">{{ formatFieldName(change.key) }}</span>
                                                </div>
                                                <div class="flex items-center gap-3">
                                                    <div class="flex-1 bg-rose-50 dark:bg-rose-900/20 px-3 py-2 rounded-xl text-xs font-bold text-rose-600 dark:text-rose-400 border border-rose-100 dark:border-rose-900/30 truncate" :title="formatValue(change.old)">
                                                        {{ formatValue(change.old) }}
                                                    </div>
                                                    <div class="w-6 h-6 rounded-full bg-white dark:bg-gray-800 shadow-sm flex items-center justify-center z-10">
                                                        <ArrowRight class="w-3 h-3 text-gray-400" />
                                                    </div>
                                                    <div class="flex-1 bg-emerald-50 dark:bg-emerald-900/20 px-3 py-2 rounded-xl text-xs font-black text-emerald-600 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-900/30 truncate" :title="formatValue(change.new)">
                                                        {{ formatValue(change.new) }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Spacer for mobile -->
                        <div class="md:hidden w-full h-8"></div>
                    </div>
                </div>

                <!-- Paginación Premium -->
                <div v-if="auditorias.links && auditorias.links.length > 3" class="flex justify-center mt-20">
                     <div class="flex items-center gap-2 p-2 bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700">
                        <template v-for="(link, key) in auditorias.links" :key="key">
                            <Link 
                                v-if="link.url" 
                                :href="link.url"
                                class="w-10 h-10 flex items-center justify-center rounded-xl text-xs transition-all duration-200"
                                :class="{
                                    'bg-indigo-600 text-white font-black shadow-lg shadow-indigo-600/30 scale-110': link.active,
                                    'text-gray-500 hover:bg-gray-50 dark:hover:bg-gray-700 font-bold': !link.active
                                }"
                                v-html="link.label.replace('Previous', '').replace('Next', '')"
                            />
                            <span v-else class="w-10 h-10 flex items-center justify-center text-gray-300 dark:text-gray-600 text-[10px] font-black" v-html="link.label.replace('Previous', '').replace('Next', '')"></span>
                        </template>
                     </div>
                </div>

            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
.tracking-widest {
    letter-spacing: 0.1em;
}

@keyframes enter {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-in {
    animation: enter 0.6s ease-out forwards;
}
</style>


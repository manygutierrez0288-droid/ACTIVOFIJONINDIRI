<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, router } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import { 
    Search, 
    CheckCircle2, 
    AlertCircle, 
    XCircle, 
    Scan, 
    User, 
    MapPin, 
    Tag,
    ChevronDown,
    Save,
    Lock
} from 'lucide-vue-next';

const props = defineProps({
    inventario: Object,
    estados: Array,
});

const search = ref('');
const selectedDetail = ref(null);

const filteredDetalles = computed(() => {
    if (!search.value) return props.inventario.detalles;
    const s = search.value.toLowerCase();
    return props.inventario.detalles.filter(d => 
        d.activo_fijo.nombre.toLowerCase().includes(s) || 
        (d.activo_fijo.codigo_inventario && d.activo_fijo.codigo_inventario.toLowerCase().includes(s))
    );
});

const stats = computed(() => {
    const total = props.inventario.detalles.length;
    const verified = props.inventario.detalles.filter(d => d.verificado).length;
    return {
        total,
        verified,
        pending: total - verified,
        percentage: total > 0 ? Math.round((verified / total) * 100) : 0
    };
});

const form = useForm({
    activo_fijo_id: '',
    estado_fisico_id: '',
    comentarios: '',
});

const selectAsset = (detalle) => {
    if (props.inventario.estado === 'cerrado') return;
    selectedDetail.value = detalle;
    form.activo_fijo_id = detalle.activo_fijo_id;
    form.estado_fisico_id = detalle.estado_fisico_id || detalle.activo_fijo.estado_id;
    form.comentarios = detalle.comentarios || '';
};

const verifyAsset = () => {
    form.post(route('inventarios.verificar', props.inventario.id), {
        onSuccess: () => {
            selectedDetail.value = null;
            form.reset();
        }
    });
};

const cerrarInventario = () => {
    if (confirm('¿Está seguro de cerrar este inventario? Ya no podrá realizar más verificaciones.')) {
        router.post(route('inventarios.cerrar', props.inventario.id));
    }
};

</script>

<template>
    <Head :title="`Inventario: ${inventario.nombre}`" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <span :class="[
                            'px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider',
                            inventario.estado === 'abierto' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'
                        ]">
                            {{ inventario.estado }}
                        </span>
                        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">{{ inventario.nombre }}</h2>
                    </div>
                    <p class="text-xs text-gray-400">Iniciado el {{ new Date(inventario.fecha_inicio).toLocaleDateString() }} por {{ inventario.user.name }}</p>
                </div>
                
                <button v-if="inventario.estado === 'abierto'" @click="cerrarInventario" class="inline-flex items-center px-4 py-2 bg-red-600 text-white text-xs font-bold uppercase rounded-lg hover:bg-red-700 shadow-sm transition-all focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                    <Lock class="w-4 h-4 mr-2" />
                    Finalizar y Cerrar
                </button>
            </div>
        </template>

        <div class="py-6 px-4 sm:px-6 lg:px-8">
            <!-- Stats Dashboard -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white dark:bg-gray-800 p-4 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 flex items-center gap-4">
                    <div class="bg-indigo-50 dark:bg-indigo-900/40 p-3 rounded-lg"><Tag class="w-6 h-6 text-indigo-600" /></div>
                    <div><p class="text-xs text-gray-500">Total Activos</p><p class="text-xl font-black">{{ stats.total }}</p></div>
                </div>
                <div class="bg-white dark:bg-gray-800 p-4 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 flex items-center gap-4">
                    <div class="bg-green-50 dark:bg-green-900/40 p-3 rounded-lg"><CheckCircle2 class="w-6 h-6 text-green-600" /></div>
                    <div><p class="text-xs text-gray-500">Verificados</p><p class="text-xl font-black text-green-600">{{ stats.verified }}</p></div>
                </div>
                <div class="bg-white dark:bg-gray-800 p-4 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 flex items-center gap-4">
                    <div class="bg-amber-50 dark:bg-amber-900/40 p-3 rounded-lg"><Clock class="w-6 h-6 text-amber-600" /></div>
                    <div><p class="text-xs text-gray-500">Pendientes</p><p class="text-xl font-black text-amber-600">{{ stats.pending }}</p></div>
                </div>
                <div class="bg-white dark:bg-gray-800 p-4 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700">
                    <div class="flex justify-between items-center mb-2"><p class="text-xs font-bold">Progreso</p><p class="text-xs font-black">{{ stats.percentage }}%</p></div>
                    <div class="w-full bg-gray-100 dark:bg-gray-700 rounded-full h-2.5"><div class="bg-indigo-600 h-2.5 rounded-full transition-all duration-500" :style="`width: ${stats.percentage}%`"></div></div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- List Section -->
                <div class="lg:col-span-2 space-y-4">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"><Search class="h-4 w-4 text-gray-400" /></div>
                        <input v-model="search" type="text" placeholder="Buscar por código o nombre del bien..." class="block w-full pl-10 pr-3 py-2 border-gray-200 dark:border-gray-700 dark:bg-gray-800 dark:text-white rounded-xl text-sm focus:ring-indigo-500 shadow-sm" />
                    </div>

                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-50 dark:bg-gray-700/50 text-[10px] uppercase font-black text-gray-500">
                                <tr>
                                    <th class="px-4 py-3 text-left">Activo</th>
                                    <th class="px-4 py-3 text-left">Ubicación</th>
                                    <th class="px-4 py-3 text-center">Estado</th>
                                    <th class="px-4 py-3"></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50 dark:divide-gray-700">
                                <tr v-for="detalle in filteredDetalles" :key="detalle.id" class="hover:bg-indigo-50/30 dark:hover:bg-indigo-900/10 cursor-pointer transition-colors" @click="selectAsset(detalle)">
                                    <td class="px-4 py-3">
                                        <div class="font-bold text-gray-900 dark:text-white">{{ detalle.activo_fijo.nombre }}</div>
                                        <div class="text-[10px] font-mono text-indigo-600 dark:text-indigo-400 uppercase tracking-tighter">{{ detalle.activo_fijo.codigo_inventario || 'SIN CÓDIGO' }}</div>
                                    </td>
                                    <td class="px-4 py-3 text-xs text-gray-500">
                                        {{ detalle.activo_fijo.ubicacion?.nombre }}
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <div v-if="detalle.verificado" class="flex flex-col items-center">
                                            <span class="inline-flex items-center px-1.5 py-0.5 rounded-full bg-green-100 text-green-700 text-[9px] font-bold uppercase"><CheckCircle2 class="w-3 h-3 mr-1" /> OK</span>
                                            <span class="text-[8px] text-gray-400 mt-0.5">{{ detalle.estado_fisico?.nombre }}</span>
                                        </div>
                                        <span v-else class="inline-flex items-center px-1.5 py-0.5 rounded-full bg-gray-100 text-gray-500 text-[9px] font-bold uppercase"><Clock class="w-3 h-3 mr-1" /> PDTE</span>
                                    </td>
                                    <td class="px-4 py-3 text-right">
                                        <ChevronDown class="w-4 h-4 text-gray-300 transform -rotate-90" />
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Verification Panel -->
                <div class="lg:col-span-1">
                    <div class="sticky top-6">
                        <div v-if="!selectedDetail" class="bg-gray-50 dark:bg-gray-900/50 border-2 border-dashed border-gray-200 dark:border-gray-700 rounded-2xl p-8 text-center flex flex-col items-center justify-center min-h-[400px]">
                            <Scan class="w-16 h-16 text-gray-300 mb-4" />
                            <h3 class="text-sm font-bold text-gray-400 mb-1 uppercase tracking-widest">Listo para Verificar</h3>
                            <p class="text-xs text-gray-400 px-6">Seleccione un activo de la lista o utilice el lector de código para comenzar la verificación.</p>
                        </div>

                        <div v-else class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-100 dark:border-gray-700 overflow-hidden animate-in fade-in slide-in-from-right-4 duration-300">
                            <div class="bg-indigo-600 p-4 text-white">
                                <div class="flex justify-between items-start">
                                    <h3 class="font-black uppercase tracking-wider text-xs">Verificar Activo</h3>
                                    <button @click="selectedDetail = null" class="hover:bg-white/20 rounded-full p-1 transition-colors"><XCircle class="w-5 h-5" /></button>
                                </div>
                                <div class="mt-4">
                                    <p class="text-lg font-bold leading-tight line-clamp-2">{{ selectedDetail.activo_fijo.nombre }}</p>
                                    <p class="font-mono text-indigo-100 text-xs mt-1 uppercase tracking-widest">{{ selectedDetail.activo_fijo.codigo_inventario }}</p>
                                </div>
                            </div>

                            <div class="p-6">
                                <form @submit.prevent="verifyAsset" class="space-y-5">
                                    <div>
                                        <label class="block text-xs font-black uppercase text-gray-500 mb-2 tracking-widest">Estado Físico del Bien</label>
                                        <div class="grid grid-cols-2 gap-2">
                                            <button v-for="est in estados" :key="est.id" type="button" @click="form.estado_fisico_id = est.id" :class="[
                                                'px-3 py-2.5 rounded-xl border-2 text-xs font-bold transition-all text-center',
                                                form.estado_fisico_id == est.id ? 'bg-indigo-50 border-indigo-500 text-indigo-700 shadow-sm' : 'border-gray-100 hover:border-gray-200 text-gray-600'
                                            ]">
                                                {{ est.nombre }}
                                            </button>
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block text-xs font-black uppercase text-gray-500 mb-2 tracking-widest">Ubicación Actual</label>
                                        <div class="flex items-center gap-2 p-3 bg-gray-50 dark:bg-gray-900/50 rounded-xl border border-gray-100 dark:border-gray-700">
                                            <MapPin class="w-4 h-4 text-gray-400" />
                                            <span class="text-xs font-bold text-gray-700 dark:text-gray-300">{{ selectedDetail.activo_fijo.ubicacion?.nombre || 'Sin Ubicación' }}</span>
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block text-xs font-black uppercase text-gray-500 mb-2 tracking-widest">Observaciones de Toma</label>
                                        <textarea v-model="form.comentarios" rows="3" class="w-full rounded-xl border-gray-200 dark:border-gray-700 dark:bg-gray-900 dark:text-white text-xs focus:ring-indigo-500" placeholder="Ej: Presenta rayones leves, falta cable de poder..."></textarea>
                                    </div>

                                    <button type="submit" :disabled="form.processing" class="w-full py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-black uppercase text-xs tracking-widest rounded-xl transition-all shadow-lg hover:shadow-indigo-500/30 flex items-center justify-center gap-2">
                                        <Save class="w-4 h-4" />
                                        Registrar Verificación
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;  
    overflow: hidden;
}
</style>

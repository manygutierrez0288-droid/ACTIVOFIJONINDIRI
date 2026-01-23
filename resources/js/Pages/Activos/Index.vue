<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import { ref, watch, onMounted } from 'vue';
import Modal from '@/Components/Modal.vue';
import ConfirmationModal from '@/Components/ConfirmationModal.vue';
import TextInput from '@/Components/TextInput.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import SearchSelect from '@/Components/SearchSelect.vue';
import DangerButton from '@/Components/DangerButton.vue';
import Checkbox from '@/Components/Checkbox.vue';
import { 
    Package, 
    MapPin, 
    Settings, 
    User, 
    Calendar, 
    DollarSign, 
    Image as ImageIcon,
    Plus,
    Filter,
    Search as SearchIcon,
    Printer,
    FileText,
    Edit3,
    Trash2,
    Eye,
    UploadCloud,
    FileCheck,
    X,
    Camera,
    AlertCircle,
    LayoutGrid,
    List
} from 'lucide-vue-next';

const props = defineProps({
    activos: Object,
    filters: Object,
    categorias: Array,
    departamentos: Array,
    ubicaciones: Array,
    marcas: Array,
    modelos: Array,
    colores: Array,
    fuentes: Array,
    proveedores: Array,
    responsables: Array,
    estados: Array,
});

// Filtering Logic
const search = ref(props.filters?.search || '');
const categoriaFilter = ref(props.filters?.categoria || '');
const departamentoFilter = ref(props.filters?.departamento || '');
const viewMode = ref(localStorage.getItem('siafnin_activos_view') || 'table');

watch(viewMode, (newMode) => {
    localStorage.setItem('siafnin_activos_view', newMode);
});

const applyFilters = () => {
    router.get(route('activos.index'), {
        search: search.value,
        categoria: categoriaFilter.value,
        departamento: departamentoFilter.value,
    }, { preserveState: true, replace: true });
};

// Formatting
const formatCurrency = (value) => {
    if (value === null || value === undefined || isNaN(value)) return 'C$ 0.00';
    return 'C$ ' + parseFloat(value).toLocaleString('es-NI', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
    });
};

// Modal Logic
const showingModal = ref(false);
const isEditing = ref(false);
const itemId = ref(null);
const currentImage = ref(null);

const form = useForm({
    nombre: '',
    imagen: null,
    categoria_id: '',
    departamento_id: '',
    ubicacion_id: '',
    marca_id: '',
    modelo_id: '',
    color_id: '',
    fuente_id: '',
    proveedor_id: '',
    responsable_id: '',
    estado_id: '',
    fecha_adquisicion: '',
    valor_adquisicion: '',
    vida_util_anios: 5,
    valor_residual: '',
    valor_residual_automatico: true,
    numero_serie: '',
});

watch(
    [() => form.valor_adquisicion, () => form.categoria_id, () => form.valor_residual_automatico],
    ([newValor, newCatId, newAuto]) => {
        const cat = props.categorias.find(c => c.id == newCatId);
        
        // Forzar 5 años excepto si la categoría indica 0 (Terrenos)
        if (cat && !isEditing.value) {
            form.vida_util_anios = (cat.vida_util_anios === 0) ? 0 : 5;
        }

        if (newAuto) {
            const pct = cat ? (parseFloat(cat.porcentaje_valor_residual) || 20) : 20;
            const val = parseFloat(newValor) || 0;
            form.valor_residual = (val * (pct / 100)).toFixed(2);
        }
    }
);

const openCreateModal = () => {
    activeSection.value = 'general';
    isEditing.value = false;
    itemId.value = null;
    currentImage.value = null;
    form.reset();
    form.clearErrors();
    showingModal.value = true;
};

const openEditModal = (item) => {
    activeSection.value = 'general';
    isEditing.value = true;
    itemId.value = item.id;
    currentImage.value = item.imagen_url;
    
    form.nombre = item.nombre;
    form.categoria_id = item.categoria_id;
    form.departamento_id = item.departamento_id;
    form.ubicacion_id = item.ubicacion_id;
    form.marca_id = item.marca_id || '';
    form.modelo_id = item.modelo_id || '';
    form.color_id = item.color_id || '';
    form.fuente_id = item.fuente_id || '';
    form.proveedor_id = item.proveedor_id || '';
    form.responsable_id = item.responsable_id || '';
    form.estado_id = item.estado_id;
    form.fecha_adquisicion = item.raw_fecha_adquisicion || '';
    form.valor_adquisicion = item.valor_adquisicion;
    form.vida_util_anios = item.vida_util_anios;
    form.valor_residual = item.valor_residual;
    form.valor_residual_automatico = !!item.valor_residual_automatico;
    form.numero_serie = item.numero_serie || '';
    
    form.clearErrors();
    showingModal.value = true;
};

const closeModal = () => {
    showingModal.value = false;
    form.reset();
};

const submitForm = () => {
    if (isEditing.value) {
        form.post(route('activos.update.post', itemId.value), {
            onSuccess: () => closeModal(),
            forceFormData: true,
        });
    } else {
        form.post(route('activos.store'), {
            onSuccess: () => closeModal(),
            forceFormData: true,
        });
    }
};

const goToNextSection = () => {
    const currentIndex = sections.findIndex(s => s.id === activeSection.value);
    if (currentIndex < sections.length - 1) {
        activeSection.value = sections[currentIndex + 1].id;
    }
};

const goToPrevSection = () => {
    const currentIndex = sections.findIndex(s => s.id === activeSection.value);
    if (currentIndex > 0) {
        activeSection.value = sections[currentIndex - 1].id;
    }
};

const handleFileSelect = (event) => {
    const file = event.target.files[0];
    if (file) form.imagen = file;
};

const handleFileDrop = (event) => {
    const file = event.dataTransfer.files[0];
    if (file && file.type.startsWith('image/')) {
        form.imagen = file;
    }
};

const getPreviewUrl = () => {
    if (form.imagen instanceof File) {
        return URL.createObjectURL(form.imagen);
    }
    return currentImage.value;
};

// Deletion Logic
const confirmingDeletion = ref(false);
const itemToDelete = ref(null);

const confirmDeletion = (id) => {
    itemToDelete.value = id;
    confirmingDeletion.value = true;
};

const deleteItem = () => {
    router.delete(route('activos.destroy', itemToDelete.value), {
        onSuccess: () => {
            confirmingDeletion.value = false;
            itemToDelete.value = null;
        },
    });
};

// Form UX: Active Section
const activeSection = ref('general');
const sections = [
    { id: 'general', label: 'Información General', icon: FileText },
    { id: 'location', label: 'Localización y Estado', icon: MapPin },
    { id: 'technical', label: 'Especificaciones Técnicas', icon: Settings },
    { id: 'financial', label: 'Datos Financieros', icon: DollarSign },
];

onMounted(() => {
    // If we're coming from a redirect (like 'Show' page edit button)
    if (props.filters?.edit_id) {
        const itemToEdit = props.activos.data.find(a => a.id == props.filters.edit_id);
        if (itemToEdit) {
            openEditModal(itemToEdit);
        }
    }
});
</script>

<template>
    <Head title="Activos Fijos" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Activos Fijos</h2>
        </template>

        <div class="py-6">
            <div class="max-w-[95%] mx-auto sm:px-4 lg:px-6">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-4">
                    
                    <!-- Advanced Filters -->
                    <div class="bg-gray-50 dark:bg-gray-900/50 p-4 rounded-xl border border-gray-100 dark:border-gray-700 mb-6">
                        <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 items-center">
                            <!-- Search -->
                            <div class="lg:col-span-4 relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <SearchIcon class="h-4 w-4 text-gray-400" />
                                </div>
                                <input v-model="search" @input="applyFilters" type="text" placeholder="Buscar por código, nombre, serie..." class="block w-full pl-10 pr-3 py-2 border-gray-200 dark:border-gray-600 dark:bg-gray-800 dark:text-white rounded-lg text-sm focus:ring-indigo-500 focus:border-indigo-500 transition-shadow">
                            </div>

                            <!-- Filters -->
                            <div class="lg:col-span-5 flex flex-col sm:flex-row gap-3">
                                <select v-model="categoriaFilter" @change="applyFilters" class="w-full rounded-lg border-gray-200 dark:border-gray-600 dark:bg-gray-800 dark:text-white px-3 py-2 text-sm focus:ring-indigo-500">
                                    <option value="">Todas las Categorías</option>
                                    <option v-for="cat in categorias" :key="cat.id" :value="cat.id">{{ cat.nombre }}</option>
                                </select>
                                <select v-model="departamentoFilter" @change="applyFilters" class="w-full rounded-lg border-gray-200 dark:border-gray-600 dark:bg-gray-800 dark:text-white px-3 py-2 text-sm focus:ring-indigo-500">
                                    <option value="">Todos los Departamentos</option>
                                    <option v-for="dep in departamentos" :key="dep.id" :value="dep.id">{{ dep.nombre }}</option>
                                </select>
                            </div>
                            
                            <!-- Actions -->
                            <div class="lg:col-span-3 flex items-center justify-end gap-3">
                                <!-- View Toggle -->
                                <div class="flex items-center bg-gray-100 dark:bg-gray-800 p-1 rounded-xl border border-gray-200 dark:border-gray-700 shrink-0">
                                    <button 
                                        @click="viewMode = 'table'" 
                                        :class="[viewMode === 'table' ? 'bg-white dark:bg-gray-700 text-indigo-600 shadow-sm' : 'text-gray-400 hover:text-gray-600']"
                                        class="p-2 rounded-lg transition-all"
                                        title="Vista de Tabla"
                                    >
                                        <List class="w-4 h-4" />
                                    </button>
                                    <button 
                                        @click="viewMode = 'grid'" 
                                        :class="[viewMode === 'grid' ? 'bg-white dark:bg-gray-700 text-indigo-600 shadow-sm' : 'text-gray-400 hover:text-gray-600']"
                                        class="p-2 rounded-lg transition-all"
                                        title="Vista de Cuadrícula"
                                    >
                                        <LayoutGrid class="w-4 h-4" />
                                    </button>
                                </div>

                                <button @click="openCreateModal" class="flex-1 lg:flex-none flex items-center justify-center gap-2 text-white bg-indigo-600 hover:bg-indigo-700 font-bold rounded-lg text-sm px-4 py-2.5 shadow-md transition-all active:scale-95 whitespace-nowrap">
                                    <Plus class="w-5 h-5" />
                                    Registrar
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Grid View Container -->
                    <div v-if="viewMode === 'grid'" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6 mb-8">
                        <div v-for="item in activos.data" :key="item.id" 
                            class="group bg-white dark:bg-gray-800 rounded-[28px] border border-gray-100 dark:border-gray-700 shadow-sm hover:shadow-2xl hover:shadow-indigo-500/10 hover:-translate-y-1 transition-all duration-300 overflow-hidden flex flex-col h-full"
                        >
                            <!-- Image Section -->
                            <div class="relative h-56 overflow-hidden">
                                <img v-if="item.imagen_url" :src="item.imagen_url" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                                <div v-else class="w-full h-full flex flex-col items-center justify-center bg-gray-50 dark:bg-gray-700/50 text-gray-300">
                                    <ImageIcon class="w-12 h-12 mb-2 opacity-20" />
                                    <span class="text-[10px] uppercase font-black tracking-widest opacity-40">Sin Fotografía</span>
                                </div>
                                
                                <!-- Floating Badges -->
                                <div class="absolute top-4 left-4 flex flex-col gap-2">
                                    <span class="px-3 py-1 bg-white/90 dark:bg-gray-900/90 backdrop-blur-md text-indigo-600 dark:text-indigo-400 text-[10px] font-black uppercase tracking-widest rounded-full shadow-sm">
                                        {{ item.categoria }}
                                    </span>
                                </div>
                                
                                <div class="absolute top-4 right-4">
                                    <span class="px-3 py-1 backdrop-blur-md rounded-full text-[10px] font-black uppercase tracking-widest shadow-sm" :class="[
                                        item.estado_nombre?.toLowerCase().includes('buen') ? 'bg-green-500/90 text-white' :
                                        item.estado_nombre?.toLowerCase().includes('regu') ? 'bg-yellow-500/90 text-white' :
                                        'bg-red-500/90 text-white'
                                    ]">
                                        {{ item.estado_nombre }}
                                    </span>
                                </div>
                                
                                <!-- Price Tag overlay -->
                                <div class="absolute bottom-4 left-4">
                                    <div class="px-3 py-1.5 bg-black/60 backdrop-blur-md text-white rounded-xl text-xs font-black">
                                        {{ formatCurrency(item.valor_neto) }}
                                    </div>
                                </div>
                            </div>

                            <!-- Content Section -->
                            <div class="p-6 flex-1 flex flex-col">
                                <div class="mb-4">
                                    <p class="text-[10px] font-black text-indigo-500 uppercase tracking-[0.2em] mb-1.5 font-mono">{{ item.codigo_inventario }}</p>
                                    <h3 class="text-lg font-black text-gray-900 dark:text-white leading-tight uppercase group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors line-clamp-2 min-h-[3rem]">{{ item.nombre }}</h3>
                                    <p class="text-xs font-medium text-gray-400 mt-1 uppercase">
    <span v-if="item.marca">{{ item.marca }}</span>
    <span v-if="item.marca && item.modelo" class="mx-1">|</span>
    <span v-if="item.modelo">{{ item.modelo }}</span>
    <span v-if="!item.marca && !item.modelo" class="italic opacity-50 text-[10px]">Sin Marca/Modelo</span>
</p>
                                </div>

                                <div class="space-y-3 pt-4 border-t border-gray-50 dark:border-gray-700/50 grow">
                                    <div class="flex items-start gap-3">
                                        <div class="p-1.5 bg-gray-50/80 dark:bg-gray-700/50 rounded-lg text-gray-400">
                                            <MapPin class="w-3.5 h-3.5" />
                                        </div>
                                        <div>
                                            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Ubicación Actual</p>
                                            <p class="text-xs font-bold text-gray-700 dark:text-gray-300">{{ item.ubicacion }}</p>
                                            <p class="text-[10px] text-gray-400 leading-tight">{{ item.departamento }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-start gap-3">
                                        <div class="p-1.5 bg-gray-50/80 dark:bg-gray-700/50 rounded-lg text-gray-400">
                                            <User class="w-3.5 h-3.5" />
                                        </div>
                                        <div>
                                            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Custodio</p>
                                            <p class="text-xs font-bold text-gray-700 dark:text-gray-300 truncate w-full">{{ item.responsable || 'No asignado' }}</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="mt-6 flex items-center justify-between gap-2 pt-4 border-t border-gray-100 dark:border-gray-700/50">
                                    <Link :href="route('activos.show', item.id)" class="flex-1 inline-flex items-center justify-center px-4 py-2.5 bg-indigo-50 hover:bg-indigo-600 text-indigo-700 hover:text-white rounded-xl text-xs font-black transition-all group/btn">
                                        <Eye class="w-3.5 h-3.5 mr-2 group-hover/btn:scale-110 transition-transform" /> DETALLES
                                    </Link>
                                    <div class="flex items-center gap-1">
                                        <a v-if="item.baja_id" :href="route('bajas.acta-baja', item.baja_id)" target="_blank" class="p-2.5 text-red-600 hover:bg-red-50 dark:hover:bg-red-900/40 rounded-xl transition-all" title="Ver Acta de Baja">
                                            <FileText class="w-4.5 h-4.5" />
                                        </a>
                                        <Link :href="route('activos.print', item.id)" target="_blank" class="p-2.5 text-gray-400 hover:text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/40 rounded-xl transition-all" title="Imprimir Ficha">
                                            <Printer class="w-4.5 h-4.5" />
                                        </Link>
                                        <button @click="openEditModal(item)" class="p-2.5 text-gray-400 hover:text-emerald-600 hover:bg-emerald-50 dark:hover:bg-emerald-900/40 rounded-xl transition-all" title="Editar">
                                            <Edit3 class="w-4.5 h-4.5" />
                                        </button>
                                        <button @click="confirmDeletion(item.id)" class="p-2.5 text-gray-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/40 rounded-xl transition-all" title="Eliminar">
                                            <Trash2 class="w-4.5 h-4.5" />
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Table (Wrapped in Condition) -->
                    <div v-if="viewMode === 'table'" class="relative overflow-x-auto shadow-md sm:rounded-xl border border-gray-100 dark:border-gray-700 custom-scrollbar">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400 min-w-[1200px]">
                            <thead class="text-xs text-white uppercase bg-gradient-to-r from-blue-600 to-indigo-600">
                                <tr>
                                    <th scope="col" class="px-4 py-3 font-bold whitespace-nowrap rounded-tl-xl">Código</th>
                                    <th scope="col" class="px-4 py-3 font-bold text-center">Imagen</th>
                                    <th scope="col" class="px-4 py-3 font-bold min-w-[250px]">Activo / Especificaciones</th>
                                    <th scope="col" class="px-4 py-3 font-bold whitespace-nowrap">Categoría</th>
                                    <th scope="col" class="px-4 py-3 font-bold min-w-[200px]">Ubicación / Depto.</th>
                                    <th scope="col" class="px-4 py-3 text-right font-bold whitespace-nowrap">Valor Neto</th>
                                    <th scope="col" class="px-4 py-3 text-center font-bold whitespace-nowrap">Estado</th>
                                    <th scope="col" class="px-4 py-3 text-center font-bold sticky right-0 z-20 shadow-[-4px_0_6px_-4px_rgba(0,0,0,0.2)] rounded-tr-xl bg-indigo-600">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                <tr v-for="item in activos.data" :key="item.id" class="bg-white dark:bg-gray-800 hover:bg-gray-50/50 dark:hover:bg-gray-700/50 transition-colors group">
                                    <td class="px-4 py-4 font-mono font-bold text-indigo-600 dark:text-indigo-400 whitespace-nowrap">
                                        {{ item.codigo_inventario }}
                                    </td>
                                    <td class="px-4 py-4 text-center">
                                        <div class="relative group/img">
                                            <img v-if="item.imagen_url" :src="item.imagen_url" class="w-12 h-12 object-cover rounded-lg shadow-sm mx-auto border border-gray-200 dark:border-gray-600 transition-transform group-hover/img:scale-150 relative z-10 cursor-zoom-in">
                                            <div v-else class="w-12 h-12 flex items-center justify-center bg-gray-100 dark:bg-gray-700 rounded-lg mx-auto text-gray-400">
                                                <ImageIcon class="w-6 h-6" />
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4">
                                        <div class="font-bold text-gray-900 dark:text-white text-base">{{ item.nombre }}</div>
                                        <div class="text-[11px] text-gray-400 mt-0.5 flex flex-wrap gap-x-1 items-center">
                                            <span v-if="item.marca">{{ item.marca }}</span>
                                            <span v-if="item.marca && item.modelo" class="text-gray-300">|</span>
                                            <span v-if="item.modelo">{{ item.modelo }}</span>
                                            <span v-if="(item.marca || item.modelo) && item.numero_serie" class="text-gray-300">|</span>
                                            <span v-if="item.numero_serie">S/N: {{ item.numero_serie }}</span>
                                            <span v-if="!item.marca && !item.modelo && !item.numero_serie" class="italic opacity-60">Sin especificaciones de fabricante</span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap">
                                        <span class="inline-flex px-2.5 py-1 text-xs font-semibold bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-md border border-gray-200 dark:border-gray-600">
                                            {{ item.categoria }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-4 capitalize">
                                        <div class="text-xs font-semibold text-gray-700 dark:text-gray-300">{{ item.ubicacion }}</div>
                                        <div class="text-[11px] text-gray-500">{{ item.departamento }}</div>
                                    </td>
                                    <td class="px-4 py-4 text-right font-mono font-bold text-emerald-600 dark:text-emerald-400 whitespace-nowrap">
                                        {{ formatCurrency(item.valor_neto) }}
                                    </td>
                                    <td class="px-4 py-4 text-center whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-wider" :class="[
                                            item.estado_nombre?.toLowerCase().includes('buen') ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-300' :
                                            item.estado_nombre?.toLowerCase().includes('regu') ? 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-300' :
                                            'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-300'
                                        ]">
                                            {{ item.estado_nombre }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-4 text-right sticky right-0 bg-white dark:bg-gray-800 group-hover:bg-gray-50 dark:group-hover:bg-gray-800 z-20 shadow-[-4px_0_6px_-4px_rgba(0,0,0,0.1)]">
                                        <div class="flex items-center justify-end gap-1">
                                            <a v-if="item.baja_id" :href="route('bajas.acta-baja', item.baja_id)" target="_blank" class="p-2 text-red-600 hover:bg-red-50 dark:hover:bg-red-900/40 rounded-lg transition-all" title="Ver Acta de Baja">
                                                <FileText class="w-4 h-4" />
                                            </a>
                                            <Link :href="route('activos.show', item.id)" class="p-2 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 dark:hover:bg-indigo-900/40 rounded-lg transition-all" title="Ver Detalles">
                                                <Eye class="w-4 h-4" />
                                            </Link>
                                            <Link :href="route('activos.print', item.id)" target="_blank" class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/40 rounded-lg transition-all" title="Ficha Técnica">
                                                <Printer class="w-4 h-4" />
                                            </Link>
                                            <button @click="openEditModal(item)" class="p-2 text-gray-400 hover:text-emerald-600 hover:bg-emerald-50 dark:hover:bg-emerald-900/40 rounded-lg transition-all" title="Editar">
                                                <Edit3 class="w-4 h-4" />
                                            </button>
                                            <button @click="confirmDeletion(item.id)" class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-900/40 rounded-lg transition-all" title="Eliminar">
                                                <Trash2 class="w-4 h-4" />
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr v-if="activos.data.length === 0">
                                    <td colspan="8" class="px-4 py-8 text-center text-gray-500 italic">
                                        No se encontraron activos con los filtros aplicados.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Create/Edit Modal -->
        <Modal :show="showingModal" @close="closeModal" maxWidth="5xl">
            <div class="flex flex-col lg:flex-row h-[85vh] overflow-hidden bg-white dark:bg-gray-800 rounded-xl">
                <!-- Sidebar Navigation -->
                <div class="w-full lg:w-72 bg-gray-50/50 dark:bg-gray-900/30 border-r border-gray-100 dark:border-gray-700 p-6 flex flex-col">
                    <div class="flex items-center gap-3 mb-10">
                        <div class="p-2.5 bg-indigo-600 rounded-xl text-white shadow-lg shadow-indigo-200 dark:shadow-none">
                            <Package class="w-6 h-6" />
                        </div>
                        <div>
                            <h2 class="text-lg font-bold text-gray-900 dark:text-gray-100 leading-tight">
                                {{ isEditing ? 'Editar Activo' : 'Nuevo Activo' }}
                            </h2>
                            <p class="text-[10px] uppercase tracking-wider font-bold text-gray-400">Gestión de Inventario</p>
                        </div>
                    </div>

                    <nav class="space-y-1.5 flex-1">
                        <button 
                            v-for="section in sections" 
                            :key="section.id"
                            @click="activeSection = section.id"
                            type="button"
                            class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all duration-200 group relative"
                            :class="activeSection === section.id 
                                ? 'bg-indigo-50 text-indigo-700 dark:bg-indigo-900/40 dark:text-indigo-300 shadow-sm' 
                                : 'text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-800 dark:text-gray-400'"
                        >
                            <div v-if="activeSection === section.id" class="absolute left-0 w-1 h-6 bg-indigo-600 rounded-r-full"></div>
                            <component :is="section.icon" class="w-5 h-5 transition-transform group-hover:scale-110" />
                            {{ section.label }}
                            
                            <!-- Validation Dot (Experimental) -->
                            <div v-if="Object.keys(form.errors).some(k => {
                                if(section.id === 'general') return ['nombre', 'imagen'].includes(k);
                                if(section.id === 'location') return ['categoria_id', 'departamento_id', 'ubicacion_id', 'estado_id', 'responsable_id'].includes(k);
                                if(section.id === 'technical') return ['marca_id', 'modelo_id', 'color_id', 'numero_serie'].includes(k);
                                if(section.id === 'financial') return ['proveedor_id', 'fuente_id', 'fecha_adquisicion', 'valor_adquisicion', 'vida_util_anios', 'valor_residual'].includes(k);
                                return false;
                            })" class="ml-auto w-2 h-2 bg-red-500 rounded-full animate-pulse"></div>
                        </button>
                    </nav>

                    <div class="pt-6 border-t border-gray-100 dark:border-gray-700 mt-auto">
                        <SecondaryButton @click="closeModal" class="w-full justify-center py-2.5">
                            Cerrar Panel
                        </SecondaryButton>
                    </div>
                </div>

                <!-- Main Content Area -->
                <form @submit.prevent="submitForm" class="flex-1 flex flex-col min-w-0 bg-white dark:bg-gray-800">
                    <div class="p-8 flex-1 overflow-y-auto custom-scrollbar">
                        <div>
                            <!-- Section: General -->
                            <div v-show="activeSection === 'general'" class="space-y-8 animate-in fade-in slide-in-from-right-4 duration-300">
                                <div class="border-b border-gray-100 dark:border-gray-700 pb-4">
                                    <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100">Información General</h3>
                                    <p class="text-sm text-gray-500 mt-1">Identificación y aspectos visuales del activo.</p>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-12 gap-8">
                                    <div class="md:col-span-8 space-y-6">
                                        <div>
                                            <InputLabel for="nombre" value="Nombre del Activo" class="text-xs font-bold uppercase tracking-wider text-gray-400 mb-1" />
                                            <TextInput id="nombre" class="mt-1 block w-full px-4 py-3 bg-gray-50 dark:bg-gray-900/50 border-gray-200 dark:border-gray-700 focus:ring-2 focus:ring-indigo-500/20" v-model="form.nombre" placeholder="Ej: Laptop Dell Latitude 5400" />
                                            <InputError class="mt-2" :message="form.errors.nombre" />
                                        </div>
                                    </div>

                                    <div class="md:col-span-12">
                                        <InputLabel value="Multimedia del Activo" class="text-xs font-bold uppercase tracking-wider text-gray-400 mb-3" />
                                        <div class="flex flex-col md:flex-row gap-6 items-start">
                                            <div class="flex-1 w-full">
                                                <label 
                                                    class="group relative flex flex-col items-center justify-center p-8 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-2xl hover:border-indigo-500 dark:hover:border-indigo-400 hover:bg-indigo-50/30 dark:hover:bg-indigo-900/10 transition-all cursor-pointer"
                                                    @dragover.prevent
                                                    @drop.prevent="handleFileDrop"
                                                >
                                                    <div class="space-y-3 text-center">
                                                        <div class="mx-auto p-4 bg-gray-100 dark:bg-gray-700 rounded-full text-gray-400 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 group-hover:bg-indigo-100 dark:group-hover:bg-indigo-900/30 transition-colors">
                                                            <UploadCloud v-if="!form.imagen" class="w-8 h-8" />
                                                            <FileCheck v-else class="w-8 h-8 text-emerald-500" />
                                                        </div>
                                                        <div class="space-y-1">
                                                            <p class="text-sm font-bold text-gray-700 dark:text-gray-200">
                                                                {{ form.imagen ? 'Imagen lista para subir' : 'Click para subir o arrastrar imagen' }}
                                                            </p>
                                                            <p class="text-[10px] text-gray-400 uppercase tracking-widest font-bold">JPG, PNG o SVG (Max 5MB)</p>
                                                        </div>
                                                    </div>
                                                    <input type="file" id="imagen" class="sr-only" @change="handleFileSelect" accept="image/*">
                                                </label>
                                                
                                                <div v-if="form.imagen" class="mt-3 flex items-center justify-between p-3 bg-emerald-50 dark:bg-emerald-900/20 rounded-xl border border-emerald-100 dark:border-emerald-800 animate-in fade-in">
                                                    <div class="flex items-center gap-2 text-emerald-700 dark:text-emerald-400 text-xs font-bold">
                                                        <FileText class="w-4 h-4" />
                                                        {{ form.imagen.name }}
                                                    </div>
                                                    <button @click.prevent="form.imagen = null" class="p-1.5 hover:bg-red-100 hover:text-red-600 rounded-lg text-gray-400 transition-colors">
                                                        <X class="w-4 h-4" />
                                                    </button>
                                                </div>
                                            </div>

                                            <div v-if="(isEditing && currentImage && !form.imagen) || form.imagen" class="shrink-0">
                                                <div class="relative group">
                                                    <div class="absolute -inset-1 bg-gradient-to-tr from-indigo-500 to-purple-500 rounded-2xl blur opacity-25 group-hover:opacity-40 transition duration-500"></div>
                                                    <img :src="getPreviewUrl()" class="relative w-40 h-40 object-cover rounded-2xl border-4 border-white dark:border-gray-800 shadow-xl">
                                                    <div class="absolute bottom-2 right-2 px-2 py-1 bg-black/60 backdrop-blur-md rounded-lg text-[10px] text-white font-bold uppercase tracking-wider">
                                                         {{ form.imagen ? 'Nueva' : 'Actual' }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <InputError class="mt-3" :message="form.errors.imagen" />
                                    </div>
                                </div>
                            </div>

                            <!-- Section: Location -->
                            <div v-show="activeSection === 'location'" class="space-y-8 animate-in fade-in slide-in-from-right-4 duration-300">
                                <div class="border-b border-gray-100 dark:border-gray-700 pb-4">
                                    <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100">Localización y Estado</h3>
                                    <p class="text-sm text-gray-500 mt-1">Defina dónde se encuentra este activo y quienes lo custodian.</p>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="space-y-2">
                                        <InputLabel for="categoria_id" value="Categoría de Activo" class="text-[10px] font-bold uppercase text-gray-400" />
                                        <SearchSelect 
                                            v-model="form.categoria_id" 
                                            :options="categorias" 
                                            placeholder="Seleccionar Categoría..." 
                                            :error="form.errors.categoria_id"
                                        />
                                        <InputError :message="form.errors.categoria_id" />
                                    </div>

                                    <div class="space-y-2">
                                        <InputLabel for="estado_id" value="Estado del Activo" class="text-[10px] font-bold uppercase text-gray-400" />
                                        <SearchSelect 
                                            v-model="form.estado_id" 
                                            :options="estados" 
                                            placeholder="Estado..." 
                                            :error="form.errors.estado_id"
                                        />
                                        <InputError :message="form.errors.estado_id" />
                                    </div>

                                    <div class="space-y-2">
                                        <InputLabel for="departamento_id" value="Departamento Administrativo" class="text-[10px] font-bold uppercase text-gray-400" />
                                        <SearchSelect 
                                            v-model="form.departamento_id" 
                                            :options="departamentos" 
                                            placeholder="Seleccionar Departamento..." 
                                            :error="form.errors.departamento_id"
                                        />
                                        <InputError :message="form.errors.departamento_id" />
                                    </div>

                                    <div class="space-y-2">
                                        <InputLabel for="ubicacion_id" value="Ubicación Física" class="text-[10px] font-bold uppercase text-gray-400" />
                                        <SearchSelect 
                                            v-model="form.ubicacion_id" 
                                            :options="ubicaciones" 
                                            placeholder="Seleccionar Ubicación..." 
                                            :error="form.errors.ubicacion_id"
                                        />
                                        <InputError :message="form.errors.ubicacion_id" />
                                    </div>

                                    <div class="md:col-span-2 space-y-2">
                                        <InputLabel for="responsable_id" value="Responsable del Bien" class="text-[10px] font-bold uppercase text-gray-400" />
                                        <div class="flex flex-col md:flex-row gap-4 items-center">
                                            <div class="p-3 bg-indigo-50 dark:bg-indigo-900/20 rounded-xl text-indigo-600 dark:text-indigo-400">
                                                <User class="w-5 h-5" />
                                            </div>
                                            <div class="flex-1 w-full">
                                                <SearchSelect 
                                                    v-model="form.responsable_id" 
                                                    :options="responsables" 
                                                    placeholder="Sin responsable asignado..." 
                                                    :error="form.errors.responsable_id"
                                                />
                                            </div>
                                        </div>
                                        <InputError :message="form.errors.responsable_id" />
                                    </div>
                                </div>
                            </div>

                            <!-- Section: Technical -->
                            <div v-show="activeSection === 'technical'" class="space-y-8 animate-in fade-in slide-in-from-right-4 duration-300">
                                <div class="border-b border-gray-100 dark:border-gray-700 pb-4">
                                    <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100">Especificaciones Técnicas</h3>
                                    <p class="text-sm text-gray-500 mt-1">Detalles de hardware, marca, modelo y otros identificadores.</p>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="space-y-2">
                                        <InputLabel for="marca_id" value="Marca (Opcional)" class="text-[10px] font-bold uppercase text-gray-400" />
                                        <select id="marca_id" v-model="form.marca_id" class="w-full rounded-xl border-gray-200 dark:bg-gray-900 dark:border-gray-700 dark:text-white px-4 py-3 text-sm focus:ring-indigo-500 transition-all">
                                            <option value="">Seleccionar Marca...</option>
                                            <option v-for="marca in marcas" :key="marca.id" :value="marca.id">{{ marca.nombre }}</option>
                                        </select>
                                        <InputError :message="form.errors.marca_id" />
                                    </div>

                                    <div class="space-y-2">
                                        <InputLabel for="modelo_id" value="Modelo (Opcional)" class="text-[10px] font-bold uppercase text-gray-400" />
                                        <select id="modelo_id" v-model="form.modelo_id" class="w-full rounded-xl border-gray-200 dark:bg-gray-900 dark:border-gray-700 dark:text-white px-4 py-3 text-sm focus:ring-indigo-500 transition-all">
                                            <option value="">Seleccionar Modelo...</option>
                                            <option v-for="mod in modelos" :key="mod.id" :value="mod.id">{{ mod.nombre }}</option>
                                        </select>
                                        <InputError :message="form.errors.modelo_id" />
                                    </div>

                                    <div class="space-y-2">
                                        <InputLabel for="color_id" value="Color / Acabado (Opcional)" class="text-[10px] font-bold uppercase text-gray-400" />
                                        <select id="color_id" v-model="form.color_id" class="w-full rounded-xl border-gray-200 dark:bg-gray-900 dark:border-gray-700 dark:text-white px-4 py-3 text-sm focus:ring-indigo-500 transition-all">
                                            <option value="">Seleccionar Color...</option>
                                            <option v-for="col in colores" :key="col.id" :value="col.id">{{ col.nombre }}</option>
                                        </select>
                                        <InputError :message="form.errors.color_id" />
                                    </div>

                                    <div class="space-y-2">
                                        <InputLabel for="numero_serie" value="Número de Serie (S/N) (Opcional)" class="text-[10px] font-bold uppercase text-gray-400" />
                                        <TextInput id="numero_serie" type="text" class="w-full rounded-xl border-gray-200 dark:bg-gray-900 dark:border-gray-700 dark:text-white px-4 py-3 text-sm focus:ring-indigo-500 transition-all font-mono" v-model="form.numero_serie" placeholder="Ej: ABC-123-XYZ" />
                                        <InputError :message="form.errors.numero_serie" />
                                    </div>
                                </div>
                            </div>

                            <!-- Section: Financial -->
                            <div v-show="activeSection === 'financial'" class="space-y-8 animate-in fade-in slide-in-from-right-4 duration-300">
                                <div class="border-b border-gray-100 dark:border-gray-700 pb-4">
                                    <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100">Datos Financieros</h3>
                                    <p class="text-sm text-gray-500 mt-1">Inversión, financiamiento y depreciación estimada.</p>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="space-y-2">
                                        <InputLabel for="proveedor_id" value="Proveedor" class="text-[10px] font-bold uppercase text-gray-400" />
                                        <select id="proveedor_id" v-model="form.proveedor_id" class="w-full rounded-xl border-gray-200 dark:bg-gray-900 dark:border-gray-700 dark:text-white px-4 py-3 text-sm focus:ring-indigo-500 transition-all">
                                            <option value="">Seleccionar Proveedor...</option>
                                            <option v-for="prov in proveedores" :key="prov.id" :value="prov.id">{{ prov.nombre }}</option>
                                        </select>
                                        <InputError :message="form.errors.proveedor_id" />
                                    </div>

                                    <div class="space-y-2">
                                        <InputLabel for="fuente_id" value="Fuente de Financiamiento" class="text-[10px] font-bold uppercase text-gray-400" />
                                        <select id="fuente_id" v-model="form.fuente_id" class="w-full rounded-xl border-gray-200 dark:bg-gray-900 dark:border-gray-700 dark:text-white px-4 py-3 text-sm focus:ring-indigo-500 transition-all">
                                            <option value="">Seleccionar Fuente...</option>
                                            <option v-for="fue in fuentes" :key="fue.id" :value="fue.id">{{ fue.nombre }}</option>
                                        </select>
                                        <InputError :message="form.errors.fuente_id" />
                                    </div>

                                    <div class="space-y-2">
                                        <InputLabel for="fecha_adquisicion" value="Fecha de Compra" class="text-[10px] font-bold uppercase text-gray-400" />
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                                                <Calendar class="w-4 h-4" />
                                            </div>
                                            <input id="fecha_adquisicion" type="date" v-model="form.fecha_adquisicion" class="w-full rounded-xl pl-10 border-gray-200 dark:bg-gray-900 dark:border-gray-700 dark:text-white px-4 py-3 text-sm focus:ring-indigo-500 transition-all">
                                        </div>
                                        <InputError :message="form.errors.fecha_adquisicion" />
                                    </div>

                                    <div class="space-y-2">
                                        <InputLabel for="valor_adquisicion" value="Costo de Adquisición (C$)" class="text-[10px] font-bold uppercase text-gray-400" />
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-emerald-500">
                                                <DollarSign class="w-4 h-4" />
                                            </div>
                                            <input id="valor_adquisicion" type="number" step="0.01" v-model="form.valor_adquisicion" class="w-full rounded-xl pl-10 border-gray-200 dark:bg-gray-900 dark:border-gray-700 dark:text-white px-4 py-3 text-sm focus:ring-indigo-500 transition-all font-bold text-gray-900 dark:text-gray-100">
                                        </div>
                                        <InputError :message="form.errors.valor_adquisicion" />
                                    </div>

                                    <div class="space-y-2">
                                        <div class="flex items-center justify-between mb-1">
                                            <InputLabel for="vida_util_anios" value="Vida Útil (Años)" class="text-[10px] font-bold uppercase text-gray-400" />
                                            <span class="text-[9px] font-black text-indigo-500 uppercase tracking-tighter bg-indigo-50 dark:bg-indigo-900/30 px-2 py-0.5 rounded-full">
                                                {{ form.vida_util_anios == 0 ? 'No Depreciable' : 'Regla Institucional (5 Años)' }}
                                            </span>
                                        </div>
                                        <input id="vida_util_anios" type="number" v-model="form.vida_util_anios" disabled class="w-full rounded-xl border-gray-200 dark:bg-gray-900 dark:border-gray-700 dark:text-white px-4 py-3 text-sm bg-gray-50/50 dark:bg-gray-800/50 opacity-70 cursor-not-allowed font-bold">
                                        <InputError :message="form.errors.vida_util_anios" />
                                    </div>

                                    <div class="space-y-2">
                                        <div class="flex items-center justify-between mb-1">
                                            <InputLabel for="valor_residual" value="Valor de Salvamento" class="text-[10px] font-bold uppercase text-gray-400" />
                                            <label class="flex items-center gap-1.5 cursor-pointer group">
                                                <Checkbox v-model:checked="form.valor_residual_automatico" class="w-4 h-4 text-indigo-600 rounded-md transition-all" />
                                                <span class="text-[9px] font-bold text-gray-400 uppercase tracking-tighter group-hover:text-indigo-500 transition-colors">Cálculo Auto.</span>
                                            </label>
                                        </div>
                                        <input id="valor_residual" type="number" step="0.01" v-model="form.valor_residual" :disabled="form.valor_residual_automatico" class="w-full rounded-xl border-gray-200 dark:bg-gray-900 dark:border-gray-700 dark:text-white px-4 py-3 text-sm focus:ring-indigo-500 transition-all font-bold" :class="form.valor_residual_automatico ? 'opacity-50 bg-gray-50/50 dark:bg-gray-800' : ''">
                                        <InputError :message="form.errors.valor_residual" />
                                    </div>

                                    <!-- Estimated Depreciation Display -->
                                    <div class="md:col-span-2 mt-4 p-4 bg-gray-50 dark:bg-gray-900/50 rounded-2xl border border-gray-100 dark:border-gray-700">
                                        <div class="flex items-center gap-2 mb-3">
                                            <div class="p-1.5 bg-emerald-100 dark:bg-emerald-900/30 rounded-lg text-emerald-600 dark:text-emerald-400">
                                                <DollarSign class="w-4 h-4" />
                                            </div>
                                            <h4 class="text-xs font-bold uppercase tracking-wider text-gray-500">Proyección de Depreciación</h4>
                                        </div>
                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <p class="text-[10px] font-bold text-gray-400 uppercase">Anual Estimada</p>
                                                <p class="text-lg font-black text-gray-900 dark:text-gray-100">
                                                    {{ formatCurrency((form.valor_adquisicion - (form.valor_residual || 0)) / (form.vida_util_anios || 1)) }}
                                                </p>
                                            </div>
                                            <div>
                                                <p class="text-[10px] font-bold text-gray-400 uppercase">Mensual Estimada</p>
                                                <p class="text-lg font-black text-indigo-600 dark:text-indigo-400">
                                                    {{ formatCurrency(((form.valor_adquisicion - (form.valor_residual || 0)) / (form.vida_util_anios || 1)) / 12) }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sticky Modal Footer -->
                    <div class="p-6 border-t border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/10 flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <SecondaryButton 
                                type="button"
                                @click="goToPrevSection" 
                                v-show="activeSection !== 'general'"
                                class="px-4 py-2.5 rounded-xl text-xs"
                            >
                                Anterior
                            </SecondaryButton>
                            
                            <div class="flex gap-2">
                                <button 
                                    v-for="section in sections" 
                                    :key="section.id"
                                    @click="activeSection = section.id"
                                    type="button"
                                    class="w-2.5 h-2.5 rounded-full transition-all duration-300"
                                    :class="activeSection === section.id ? 'bg-indigo-600 w-8' : 'bg-gray-300 dark:bg-gray-600'"
                                ></button>
                            </div>

                            <SecondaryButton 
                                type="button"
                                @click="goToNextSection" 
                                v-show="activeSection !== 'financial'"
                                class="px-4 py-2.5 rounded-xl text-xs"
                            >
                                Siguiente
                            </SecondaryButton>
                        </div>

                        <div class="flex items-center gap-3">
                            <!-- Error Summary -->
                            <div v-if="Object.keys(form.errors).length > 0" class="hidden md:flex items-center gap-2 px-3 py-1.5 bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 rounded-lg border border-red-100 dark:border-red-900/30 animate-pulse">
                                <AlertCircle class="w-3.5 h-3.5" />
                                <span class="text-[10px] font-bold uppercase tracking-tight">Faltan campos obligatorios</span>
                            </div>

                            <SecondaryButton @click="closeModal" class="px-6 py-2.5 rounded-xl border-none hover:bg-red-50 hover:text-red-600 dark:hover:bg-red-900/20 shadow-none text-gray-400 transition-all">
                                Cancelar
                            </SecondaryButton>
                            <PrimaryButton 
                                type="submit" 
                                class="px-10 py-3 rounded-xl shadow-lg shadow-indigo-200 dark:shadow-none hover:translate-y-[-2px] active:translate-y-0 transition-all font-bold" 
                                :disabled="form.processing"
                                :class="{ 'opacity-50 grayscale cursor-not-allowed': form.processing }"
                            >
                                <div class="flex items-center gap-2">
                                    <span v-if="form.processing">Procesando...</span>
                                    <template v-else>
                                        <span>{{ isEditing ? 'Actualizar Activo' : 'Registrar Activo' }}</span>
                                    </template>
                                </div>
                            </PrimaryButton>
                        </div>
                    </div>
                </form>
            </div>
        </Modal>

        <!-- Deletion Confirmation Modal -->
        <ConfirmationModal :show="confirmingDeletion" @close="confirmingDeletion = false">
            <template #title>
                Eliminar Activo Fijo
            </template>
            <template #content>
                ¿Está seguro de que desea eliminar este activo? Esta acción eliminará permanentemente todos los registros asociados y no se puede deshacer.
            </template>
            <template #footer>
                <DangerButton @click="deleteItem" class="ml-3">
                    Eliminar Permanentemente
                </DangerButton>
                <SecondaryButton @click="confirmingDeletion = false">
                    Cancelar
                </SecondaryButton>
            </template>
        </ConfirmationModal>

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
    background: #e2e8f0;
    border-radius: 10px;
}
.dark .custom-scrollbar::-webkit-scrollbar-thumb {
    background: #334155;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes slideInRight {
    from { transform: translateX(20px); opacity: 0; }
    to { transform: translateX(0); opacity: 1; }
}

.animate-in {
    animation-duration: 400ms;
    animation-fill-mode: both;
}

.fade-in {
    animation-name: fadeIn;
}

.slide-in-from-right-4 {
    animation-name: slideInRight;
}
</style>


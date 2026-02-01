<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import { ref, watch, onMounted, computed } from 'vue';
import Modal from '@/Components/Modal.vue';
import ConfirmationModal from '@/Components/ConfirmationModal.vue';
import TextInput from '@/Components/TextInput.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import SearchSelect from '@/Components/SearchSelect.vue';
import { 
    Plus, 
    Search, 
    Map, 
    Settings, 
    DollarSign, 
    User, 
    ChevronRight,
    AlertCircle,
    CheckCircle2,
    Calendar,
    ArrowRight,
    ArrowLeft,
    UploadCloud,
    FileCheck,
    X,
    Camera,
    Info,
    MoveUpRight,
    Maximize2
} from 'lucide-vue-next';

const props = defineProps({
    terrenos: Object,
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

// Modal State
const showModal = ref(false);
const isEditing = ref(false);
const itemId = ref(null);
const activeSection = ref('general');
const currentImage = ref(null);

// Form
const form = useForm({
    dominio: '',
    numero_escritura: '',
    area_metros_cuadrados: 0,
    frente_metros: 0,
    fondo_metros: 0,
    lindero_norte: '',
    lindero_sur: '',
    lindero_este: '',
    lindero_oeste: '',
    coordenadas_gps: '',
    uso_suelo: '',
    zonificacion: '',
    valor_catastral: 0,
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
    fecha_adquisicion: new Date().toISOString().split('T')[0],
    valor_adquisicion: 0,
    vida_util_anios: 0,
    valor_residual: 0,
    codigo_inventario: '',
    numero_serie: '',
    imagen: null,
});

// Sections
const sections = [
    { id: 'general', label: 'Información General', icon: Info },
    { id: 'technical', label: 'Especificaciones Técnicas', icon: Maximize2 },
    { id: 'assignment', label: 'Asignación y Ubicación', icon: User },
    { id: 'financial', label: 'Datos Financieros', icon: DollarSign },
];

// Open/Close logic
const openCreateModal = () => {
    isEditing.value = false;
    itemId.value = null;
    currentImage.value = null;
    activeSection.value = 'general';
    form.reset();
    
    // Set default state to 'Bueno'
    const buenoState = props.estados.find(e => e.nombre.toLowerCase().includes('bueno'));
    if (buenoState) {
        form.estado_id = buenoState.id;
    } else if (props.estados.length > 0) {
        form.estado_id = props.estados[0].id;
    }
    
    showModal.value = true;
};

const openEditModal = (item) => {
    isEditing.value = true;
    itemId.value = item.id;
    activeSection.value = 'general';
    currentImage.value = item.activo_fijo?.imagen_url;
    
    // Map data to form
    const af = item.activo_fijo;
    form.nombre = af.nombre;
    form.dominio = item.dominio || '';
    form.numero_escritura = item.numero_escritura;
    form.area_metros_cuadrados = item.area_metros_cuadrados;
    form.frente_metros = item.frente_metros;
    form.fondo_metros = item.fondo_metros;
    form.lindero_norte = item.lindero_norte;
    form.lindero_sur = item.lindero_sur;
    form.lindero_este = item.lindero_este;
    form.lindero_oeste = item.lindero_oeste;
    form.coordenadas_gps = item.coordenadas_gps;
    form.uso_suelo = item.uso_suelo;
    form.zonificacion = item.zonificacion;
    form.valor_catastral = item.valor_catastral;
    
    form.categoria_id = af.categoria_id;
    form.departamento_id = af.departamento_id;
    form.ubicacion_id = af.ubicacion_id;
    form.marca_id = af.marca_id;
    form.modelo_id = af.modelo_id;
    form.color_id = af.color_id;
    form.fuente_id = af.fuente_id;
    form.proveedor_id = af.proveedor_id;
    form.responsable_id = af.responsable_id;
    form.estado_id = af.estado_id;
    form.fecha_adquisicion = af.fecha_adquisicion;
    form.valor_adquisicion = af.valor_adquisicion;
    form.vida_util_anios = af.vida_util_anios;
    form.valor_residual = af.valor_residual;
    form.codigo_inventario = af.codigo_inventario;
    form.numero_serie = af.numero_serie;
    form.imagen = null;

    showModal.value = true;
};

const closeModal = () => {
    showModal.value = false;
    form.clearErrors();
};

const submitForm = () => {
    if (isEditing.value) {
        form.post(route('terrenos.update', itemId.value), {
            onSuccess: () => closeModal(),
            forceFormData: true,
        });
    } else {
        form.post(route('terrenos.store'), {
            onSuccess: () => closeModal(),
            forceFormData: true,
        });
    }
};

// Wizard Navigation
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

// File handling
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

// UI Helpers
const formatCurrency = (value) => {
    if (!value) return 'C$ 0.00';
    return new Intl.NumberFormat('es-NI', {
        style: 'currency',
        currency: 'NIO',
    }).format(value).replace('NIO', 'C$');
};

const formatArea = (value) => {
    if (!value) return '0.00 m²';
    return new Intl.NumberFormat('es-NI', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
    }).format(value) + ' m²';
};

const deleteItem = (id) => {
    if (confirm('¿Estás seguro de eliminar este terreno?')) {
        router.delete(route('terrenos.destroy', id), {
            preserveScroll: true,
        });
    }
};

onMounted(() => {
    if (props.filters?.edit_id) {
        const itemToEdit = props.terrenos.data.find(v => v.id == props.filters.edit_id);
        if (itemToEdit) {
            openEditModal(itemToEdit);
        }
    }
});
</script>

<template>
    <Head title="Listado de Terrenos" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Gestión Catastral de Terrenos</h2>
        </template>
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-2 sm:p-4">
            
             <!-- Actions Toolbar -->
            <div class="bg-gray-50 dark:bg-gray-900/50 p-3 rounded-xl border border-gray-100 dark:border-gray-700 mb-4 flex justify-between items-center">
                 <div>
                    <h3 class="text-sm font-bold text-gray-700 dark:text-gray-300 flex items-center gap-2">
                        <Map class="w-4 h-4 text-emerald-500" />
                        Inventario de Propiedades
                    </h3>
                 </div>
                 <button @click="openCreateModal" class="flex items-center gap-2 text-white bg-indigo-600 hover:bg-indigo-700 font-bold rounded-lg text-xs px-4 py-2 shadow-sm hover:shadow transition-all group">
                     <Plus class="w-4 h-4 group-hover:rotate-90 transition-transform" />
                     Registrar Terreno
                 </button>
            </div>

            <div class="relative overflow-hidden shadow-sm rounded-xl border border-gray-100 dark:border-gray-700">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-white uppercase bg-gradient-to-r from-blue-600 to-indigo-600">
                        <tr>
                            <th class="px-3 py-2.5 font-bold rounded-tl-xl">Escritura</th>
                            <th class="px-3 py-2.5 font-bold">Terreno</th>
                            <th class="px-3 py-2.5 font-bold">Dimensiones</th>
                            <th class="px-3 py-2.5 font-bold">Ubicación</th>
                            <th class="px-3 py-2.5 text-right font-bold">Valor/m²</th>
                            <th class="px-3 py-2.5 text-center font-bold">Estado</th>
                            <th class="px-3 py-2.5 text-center font-bold rounded-tr-xl sticky right-0 z-20 shadow-[-4px_0_6px_-4px_rgba(0,0,0,0.2)] bg-indigo-600">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 dark:divide-gray-700">
                        <tr v-for="terreno in terrenos.data" :key="terreno.id" class="bg-white dark:bg-gray-800 hover:bg-gray-50/80 dark:hover:bg-gray-700/50 transition-all">
                            <td class="px-3 py-2">
                                <div class="px-3 py-1 bg-amber-100 dark:bg-amber-900/30 text-amber-800 dark:text-amber-200 font-bold font-mono rounded-lg border border-amber-200 dark:border-amber-700 text-center shadow-sm w-fit">
                                    {{ terreno.numero_escritura }}
                                </div>
                            </td>
                            <td class="px-3 py-2">
                                <div class="flex items-center gap-3">
                                    <div v-if="terreno.activo_fijo?.imagen_url" class="w-10 h-10 rounded-lg overflow-hidden border border-gray-100 bg-gray-50">
                                        <img :src="terreno.activo_fijo.imagen_url" class="w-full h-full object-cover" alt="">
                                    </div>
                                    <div v-else class="w-10 h-10 rounded-lg bg-gray-100 dark:bg-gray-700 flex items-center justify-center border border-gray-200 dark:border-gray-600">
                                        <Map class="w-5 h-5 text-gray-400" />
                                    </div>
                                    <div>
                                        <span class="font-bold text-gray-900 dark:text-white block">{{ terreno.activo_fijo?.nombre }}</span>
                                        <div class="flex items-center gap-2">
                                            <span class="text-[10px] text-gray-500 uppercase font-medium">{{ terreno.uso_suelo || 'S/U' }}</span>
                                            <span v-if="terreno.dominio" class="text-[9px] px-1.5 py-0.5 bg-blue-50 text-blue-600 rounded-md font-bold uppercase">{{ terreno.dominio }}</span>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-3 py-2 text-xs">
                                <div class="font-bold text-indigo-600 dark:text-indigo-400">{{ formatArea(terreno.area_metros_cuadrados) }}</div>
                                <div v-if="terreno.frente_metros && terreno.fondo_metros" class="text-[10px] text-gray-500 font-medium">
                                    {{ terreno.frente_metros }}m × {{ terreno.fondo_metros }}m
                                </div>
                            </td>
                            <td class="px-3 py-2 text-xs">
                                <div class="font-semibold text-gray-800 dark:text-gray-200">{{ terreno.activo_fijo?.ubicacion?.nombre }}</div>
                                <div class="text-[10px] text-gray-500 italic">{{ terreno.activo_fijo?.departamento?.nombre || 'Sin Departamento' }}</div>
                            </td>
                            <td class="px-3 py-2 text-right font-mono text-xs font-bold text-emerald-600 dark:text-emerald-400">
                                {{ formatCurrency(terreno.valor_por_metro_cuadrado) }}
                            </td>
                             <td class="px-3 py-2 text-center">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold tracking-tight uppercase" :class="[
                                    terreno.activo_fijo?.estado?.nombre?.toLowerCase().includes('buen') || terreno.activo_fijo?.estado?.nombre?.toLowerCase().includes('activo') ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-300' :
                                    'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-300'
                                ]">
                                    {{ terreno.activo_fijo?.estado?.nombre }}
                                </span>
                            </td>
                            <td class="px-3 py-2 text-center">
                                <div class="flex items-center justify-center gap-1">
                                    <Link :href="route('terrenos.show', terreno.id)" class="p-1.5 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 dark:hover:bg-indigo-900/40 rounded-lg transition-all" title="Ver Detalles"><Info class="w-4 h-4" /></Link>
                                    <button @click="openEditModal(terreno)" class="p-1.5 text-gray-400 hover:text-emerald-600 hover:bg-emerald-50 dark:hover:bg-emerald-900/40 rounded-lg transition-all" title="Editar"><Settings class="w-4 h-4" /></button>
                                    <button @click="deleteItem(terreno.id)" class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-900/40 rounded-lg transition-all" title="Eliminar"><X class="w-4 h-4" /></button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
        </div>

        <!-- Create/Edit Modal -->
        <Modal :show="showModal" @close="closeModal" max-width="5xl">
            <div class="flex flex-col h-[90vh] md:h-[80vh] bg-white dark:bg-gray-800 rounded-2xl overflow-hidden shadow-2xl transition-all duration-300">
                <!-- Header -->
                <div class="p-6 border-b border-gray-100 dark:border-gray-700 bg-white dark:bg-gray-800 flex items-center justify-between sticky top-0 z-20">
                    <div class="flex items-center gap-4">
                        <div class="p-3 bg-emerald-50 dark:bg-emerald-900/30 rounded-2xl">
                            <Map class="w-6 h-6 text-emerald-600 dark:text-emerald-400" />
                        </div>
                        <div>
                            <h2 class="text-xl font-extrabold text-gray-900 dark:text-white tracking-tight">
                                {{ isEditing ? 'Editar Terreno' : 'Nuevo Terreno' }}
                            </h2>
                            <p class="text-xs text-gray-500 font-medium uppercase tracking-wider">Gestión de Activos Inmobiliarios</p>
                        </div>
                    </div>
                </div>

                <div class="flex flex-1 overflow-hidden">
                    <!-- Sidebar Navigation -->
                    <div class="w-20 md:w-64 border-r border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/20 overflow-y-auto">
                        <nav class="p-4 space-y-2">
                            <button 
                                v-for="section in sections" 
                                :key="section.id"
                                @click="activeSection = section.id"
                                class="w-full flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 group relative"
                                :class="activeSection === section.id 
                                    ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-200 dark:shadow-none' 
                                    : 'text-gray-500 hover:bg-white dark:hover:bg-gray-800 hover:text-indigo-600'"
                            >
                                <component :is="section.icon" class="w-5 h-5" />
                                <span class="hidden md:block text-sm font-bold tracking-tight">{{ section.label }}</span>
                                
                                <!-- Error indicator -->
                                <div 
                                    v-if="Object.keys(form.errors).some(k => 
                                        (section.id === 'general' && ['nombre', 'numero_escritura', 'categoria_id', 'imagen'].includes(k)) ||
                                        (section.id === 'technical' && ['area_metros_cuadrados', 'frente_metros', 'fondo_metros', 'lindero_norte', 'lindero_sur', 'lindero_este', 'lindero_oeste', 'uso_suelo', 'zonificacion'].includes(k)) ||
                                        (section.id === 'assignment' && ['departamento_id', 'ubicacion_id', 'responsable_id', 'estado_id'].includes(k)) ||
                                        (section.id === 'financial' && ['fecha_adquisicion', 'valor_adquisicion', 'valor_catastral', 'vida_util_anios'].includes(k))
                                    )"
                                    class="absolute top-2 right-2 w-2 h-2 bg-red-500 rounded-full border-2 border-white dark:border-gray-800"
                                ></div>
                            </button>
                        </nav>
                    </div>

                    <!-- Form Content -->
                    <div class="flex-1 overflow-y-auto p-4 md:p-8 bg-white dark:bg-gray-800">
                        <form @submit.prevent="submitForm">
                            <!-- Section: General -->
                            <div v-show="activeSection === 'general'" class="space-y-6 animate-in fade-in slide-in-from-right-4 duration-300">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="space-y-2">
                                        <InputLabel value="Nombre Identificador" class="text-xs font-bold uppercase text-gray-400" />
                                        <TextInput 
                                            type="text" 
                                            v-model="form.nombre" 
                                            class="w-full border-gray-200 rounded-xl focus:ring-indigo-500" 
                                            placeholder="Ej: Terreno Central Alcaldía"
                                        />
                                        <InputError :message="form.errors.nombre" />
                                    </div>
                                    <div class="space-y-2">
                                        <InputLabel value="Número de Escritura" class="text-xs font-bold uppercase text-gray-400" />
                                        <TextInput 
                                            type="text" 
                                            v-model="form.numero_escritura" 
                                            class="w-full border-gray-200 rounded-xl focus:ring-indigo-500 font-mono font-bold" 
                                            placeholder="Ej: ESC-2024-001"
                                        />
                                        <InputError :message="form.errors.numero_escritura" />
                                    </div>

                                    <div class="space-y-2">
                                        <InputLabel value="Categoría de Activo" class="text-xs font-bold uppercase text-gray-400" />
                                        <SearchSelect 
                                            v-model="form.categoria_id" 
                                            :options="categorias" 
                                            placeholder="Seleccionar Categoría..." 
                                            :error="form.errors.categoria_id"
                                        />
                                        <InputError :message="form.errors.categoria_id" />
                                    </div>

                                    <div class="space-y-2">
                                        <InputLabel value="Código de Inventario" class="text-xs font-bold uppercase text-gray-400" />
                                        <TextInput 
                                            type="text" 
                                            v-model="form.codigo_inventario" 
                                            class="w-full border-gray-200 rounded-xl focus:ring-indigo-500" 
                                            placeholder="Ej: INV-TER-001"
                                        />
                                        <InputError :message="form.errors.codigo_inventario" />
                                    </div>
                                </div>

                                <!-- Multimedia Upload -->
                                <div class="space-y-4">
                                    <InputLabel value="Plancha o Fotografía del Terreno" class="text-xs font-bold uppercase text-gray-400" />
                                    <div 
                                        @dragover.prevent 
                                        @drop.prevent="handleFileDrop"
                                        class="relative group border-2 border-dashed border-gray-200 dark:border-gray-700 rounded-3xl p-8 transition-all hover:border-emerald-400 hover:bg-emerald-50/30 dark:hover:bg-emerald-900/10 flex flex-col items-center justify-center gap-4 overflow-hidden"
                                    >
                                        <div v-if="getPreviewUrl()" class="absolute inset-0">
                                            <img :src="getPreviewUrl()" class="w-full h-full object-cover" />
                                            <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-4">
                                                <button type="button" @click="form.imagen = null; currentImage = null" class="p-3 bg-red-500 text-white rounded-2xl hover:scale-110 transition-transform"><X class="w-6 h-6" /></button>
                                                <label class="p-3 bg-white text-gray-900 rounded-2xl hover:scale-110 transition-transform cursor-pointer"><Camera class="w-6 h-6" /><input type="file" @change="handleFileSelect" class="hidden" accept="image/*" /></label>
                                            </div>
                                        </div>
                                        <template v-else>
                                            <div class="p-4 bg-emerald-50 dark:bg-emerald-900/30 rounded-full group-hover:scale-110 transition-transform"><UploadCloud class="w-10 h-10 text-emerald-600 dark:text-emerald-400" /></div>
                                            <div class="text-center">
                                                <p class="text-sm font-bold text-gray-700 dark:text-gray-300">Arrastra un plano o imagen</p>
                                                <p class="text-xs text-gray-500 mt-1">Soporta JPG, PNG (Max. 5MB)</p>
                                            </div>
                                            <input type="file" @change="handleFileSelect" class="absolute inset-0 opacity-0 cursor-pointer" accept="image/*" />
                                        </template>
                                    </div>
                                    <InputError :message="form.errors.imagen" />
                                </div>
                            </div>

                            <!-- Section: Technical -->
                            <div v-show="activeSection === 'technical'" class="space-y-6 animate-in fade-in slide-in-from-right-4 duration-300">
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                    <div class="space-y-2">
                                        <InputLabel value="Área Total (m²)" class="text-xs font-bold uppercase text-gray-400" />
                                        <div class="relative">
                                            <Maximize2 class="absolute left-4 top-3.5 w-4 h-4 text-gray-400" />
                                            <TextInput type="number" step="0.01" v-model="form.area_metros_cuadrados" class="w-full pl-11 border-gray-200 rounded-xl" />
                                        </div>
                                        <InputError :message="form.errors.area_metros_cuadrados" />
                                    </div>
                                    <div class="space-y-2">
                                        <InputLabel value="Frente (m)" class="text-xs font-bold uppercase text-gray-400" />
                                        <TextInput type="number" step="0.01" v-model="form.frente_metros" class="w-full border-gray-200 rounded-xl" />
                                        <InputError :message="form.errors.frente_metros" />
                                    </div>
                                    <div class="space-y-2">
                                        <InputLabel value="Fondo (m)" class="text-xs font-bold uppercase text-gray-400" />
                                        <TextInput type="number" step="0.01" v-model="form.fondo_metros" class="w-full border-gray-200 rounded-xl" />
                                        <InputError :message="form.errors.fondo_metros" />
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="space-y-2">
                                        <InputLabel value="Lindero Norte" class="text-xs font-bold uppercase text-gray-400" />
                                        <TextInput type="text" v-model="form.lindero_norte" class="w-full border-gray-200 rounded-xl shadow-sm" />
                                    </div>
                                    <div class="space-y-2">
                                        <InputLabel value="Lindero Sur" class="text-xs font-bold uppercase text-gray-400" />
                                        <TextInput type="text" v-model="form.lindero_sur" class="w-full border-gray-200 rounded-xl shadow-sm" />
                                    </div>
                                    <div class="space-y-2">
                                        <InputLabel value="Lindero Este" class="text-xs font-bold uppercase text-gray-400" />
                                        <TextInput type="text" v-model="form.lindero_este" class="w-full border-gray-200 rounded-xl shadow-sm" />
                                    </div>
                                    <div class="space-y-2">
                                        <InputLabel value="Lindero Oeste" class="text-xs font-bold uppercase text-gray-400" />
                                        <TextInput type="text" v-model="form.lindero_oeste" class="w-full border-gray-200 rounded-xl shadow-sm" />
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                    <div class="space-y-2">
                                        <InputLabel value="Uso de Suelo" class="text-xs font-bold uppercase text-gray-400" />
                                        <select v-model="form.uso_suelo" class="w-full border-gray-200 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-xl focus:ring-indigo-500">
                                            <option value="">Seleccionar Uso</option>
                                            <option value="Residencial">Residencial</option>
                                            <option value="Comercial">Comercial</option>
                                            <option value="Industrial">Industrial</option>
                                            <option value="Equipamiento Público">Equipamiento Público</option>
                                            <option value="Agrícola">Agrícola</option>
                                        </select>
                                    </div>
                                    <div class="space-y-2">
                                        <InputLabel value="Zonificación" class="text-xs font-bold uppercase text-gray-400" />
                                        <TextInput type="text" v-model="form.zonificacion" class="w-full border-gray-200 rounded-xl" />
                                    </div>
                                    <div class="space-y-2">
                                        <InputLabel value="Coordenadas GPS" class="text-xs font-bold uppercase text-gray-400" />
                                        <TextInput type="text" v-model="form.coordenadas_gps" class="w-full border-gray-200 rounded-xl" placeholder="Ej: 12.134, -86.234" />
                                    </div>
                                </div>
                            </div>

                            <!-- Section: Assignment -->
                            <div v-show="activeSection === 'assignment'" class="space-y-6 animate-in fade-in slide-in-from-right-4 duration-300">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="space-y-2">
                                        <InputLabel value="Departamento Administrativo" class="text-xs font-bold uppercase text-gray-400" />
                                        <SearchSelect 
                                            v-model="form.departamento_id" 
                                            :options="departamentos" 
                                            placeholder="Seleccionar Departamento..." 
                                            :error="form.errors.departamento_id"
                                        />
                                        <InputError :message="form.errors.departamento_id" />
                                    </div>
                                    <div class="space-y-2">
                                        <InputLabel value="Ubicación Física" class="text-xs font-bold uppercase text-gray-400" />
                                        <SearchSelect 
                                            v-model="form.ubicacion_id" 
                                            :options="ubicaciones" 
                                            placeholder="Seleccionar Ubicación..." 
                                            :error="form.errors.ubicacion_id"
                                        />
                                        <InputError :message="form.errors.ubicacion_id" />
                                    </div>
                                    <div class="space-y-2">
                                        <InputLabel value="Responsable del Bien" class="text-xs font-bold uppercase text-gray-400" />
                                        <SearchSelect 
                                            v-model="form.responsable_id" 
                                            :options="responsables" 
                                            placeholder="Seleccionar Responsable..." 
                                            :error="form.errors.responsable_id"
                                        />
                                        <InputError :message="form.errors.responsable_id" />
                                    </div>

                                    <div class="space-y-2">
                                        <InputLabel value="Propiedad / Dominio" class="text-xs font-bold uppercase text-gray-400" />
                                        <select v-model="form.dominio" class="w-full border-gray-200 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-xl focus:ring-indigo-500">
                                            <option value="">Seleccionar Titularidad...</option>
                                            <option value="Alcaldía Municipal">Alcaldía Municipal</option>
                                            <option value="Gobierno Central">Gobierno Central</option>
                                            <option value="Otros">Otros</option>
                                        </select>
                                        <InputError :message="form.errors.dominio" />
                                    </div>
                                </div>
                            </div>

                            <!-- Section: Financial -->
                            <div v-show="activeSection === 'financial'" class="space-y-6 animate-in fade-in slide-in-from-right-4 duration-300">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="space-y-2">
                                        <InputLabel value="Fecha de Adquisición" class="text-xs font-bold uppercase text-gray-400" />
                                        <div class="relative">
                                            <Calendar class="absolute left-4 top-3.5 w-4 h-4 text-gray-400" />
                                            <TextInput type="date" v-model="form.fecha_adquisicion" class="w-full pl-11 border-gray-200 rounded-xl focus:ring-indigo-500" />
                                        </div>
                                        <InputError :message="form.errors.fecha_adquisicion" />
                                    </div>
                                    <div class="space-y-2">
                                        <InputLabel value="Valor de Adquisición/Libros" class="text-xs font-bold uppercase text-gray-400" />
                                        <div class="relative">
                                            <span class="absolute left-4 top-3 text-gray-500 font-bold">C$</span>
                                            <TextInput type="number" step="0.01" v-model="form.valor_adquisicion" class="w-full pl-12 border-gray-200 rounded-xl focus:ring-indigo-500" />
                                        </div>
                                        <InputError :message="form.errors.valor_adquisicion" />
                                    </div>
                                    <div class="space-y-2">
                                        <InputLabel value="Valor Catastral" class="text-xs font-bold uppercase text-gray-400" />
                                        <div class="relative">
                                            <span class="absolute left-4 top-3 text-gray-500 font-bold">C$</span>
                                            <TextInput type="number" step="0.01" v-model="form.valor_catastral" class="w-full pl-12 border-gray-200 rounded-xl focus:ring-indigo-500" />
                                        </div>
                                        <InputError :message="form.errors.valor_catastral" />
                                    </div>
                                </div>

                                <!-- Financial Summary Card -->
                                <div class="bg-indigo-50 dark:bg-indigo-900/10 rounded-2xl p-6 border border-indigo-100 dark:border-indigo-900/30">
                                    <h4 class="text-xs font-bold text-indigo-700 dark:text-indigo-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                                        <DollarSign class="w-4 h-4" /> Resumen de Valorización
                                    </h4>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <p class="text-[10px] text-gray-500 uppercase font-bold">Valor por Metro Cuadrado</p>
                                            <p class="text-lg font-extrabold text-gray-900 dark:text-white">
                                                {{ formatCurrency(form.valor_adquisicion / (form.area_metros_cuadrados || 1)) }}
                                            </p>
                                        </div>
                                        <div>
                                            <p class="text-[10px] text-gray-500 uppercase font-bold">Diferencia Catastral</p>
                                            <p class="text-lg font-extrabold" :class="form.valor_adquisicion > form.valor_catastral ? 'text-emerald-600' : 'text-amber-600'">
                                                {{ formatCurrency(Math.abs(form.valor_adquisicion - form.valor_catastral)) }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="hidden" id="submit-form-btn"></button>
                        </form>
                    </div>
                </div>

                <!-- Footer -->
                <div class="p-6 border-t border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/10 flex items-center justify-between sticky bottom-0 z-20">
                    <div class="flex items-center gap-4">
                        <SecondaryButton 
                            type="button"
                            @click="goToPrevSection" 
                            v-show="activeSection !== 'general'"
                            class="px-4 py-2.5 rounded-xl text-xs"
                        >
                            <ArrowLeft class="w-3.5 h-3.5 mr-2" /> Anterior
                        </SecondaryButton>
                        
                        <div class="flex gap-2">
                            <button 
                                v-for="section in sections" 
                                :key="section.id"
                                @click="activeSection = section.id"
                                type="button"
                                class="w-2 h-2 rounded-full transition-all duration-300"
                                :class="activeSection === section.id ? 'bg-indigo-600 w-6' : 'bg-gray-300 dark:bg-gray-600'"
                            ></button>
                        </div>

                        <SecondaryButton 
                            type="button"
                            @click="goToNextSection" 
                            v-show="activeSection !== 'financial'"
                            class="px-4 py-2.5 rounded-xl text-xs"
                        >
                            Siguiente <ArrowRight class="w-3.5 h-3.5 ml-2" />
                        </SecondaryButton>
                    </div>

                    <div class="flex items-center gap-3">
                        <div v-if="Object.keys(form.errors).length > 0" class="hidden md:flex items-center gap-2 px-3 py-1.5 bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 rounded-lg border border-red-100 dark:border-red-900/30 animate-pulse">
                            <AlertCircle class="w-3.5 h-3.5" />
                            <span class="text-[10px] font-bold uppercase tracking-tight">Campos obligatorios pendientes</span>
                        </div>

                        <SecondaryButton @click="closeModal" class="px-6 py-2.5 rounded-xl border-none hover:bg-red-50 hover:text-red-600 shadow-none text-gray-400 transition-all">
                            Cancelar
                        </SecondaryButton>
                        <PrimaryButton 
                            @click="submitForm"
                            class="px-10 py-3 rounded-xl shadow-lg shadow-indigo-200 dark:shadow-none hover:translate-y-[-2px] active:translate-y-0 transition-all font-bold" 
                            :disabled="form.processing"
                        >
                            <div class="flex items-center gap-2">
                                <span v-if="form.processing">Procesando...</span>
                                <template v-else>
                                    <CheckCircle2 class="w-4 h-4" />
                                    <span>{{ isEditing ? 'Actualizar Terreno' : 'Guardar Propiedad' }}</span>
                                </template>
                            </div>
                        </PrimaryButton>
                    </div>
                </div>
            </div>
        </Modal>
    </AuthenticatedLayout>
</template>

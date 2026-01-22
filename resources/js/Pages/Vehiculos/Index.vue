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
    Car, 
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
    Info
} from 'lucide-vue-next';

const props = defineProps({
    vehiculos: Object,
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
const showConfirmDelete = ref(false);
const itemToDelete = ref(null);

// Form
const form = useForm({
    nombre: '',
    placa: '',
    numero_circulacion: '',
    anio: new Date().getFullYear(),
    kilometraje: 0,
    tipo_combustible: '',
    capacidad_pasajeros: 5,
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
    vida_util_anios: 5,
    valor_residual: 0,
    codigo_inventario: '',
    numero_serie: '',
    imagen: null,
});

// Sections
const sections = [
    { id: 'general', label: 'Información General', icon: Info },
    { id: 'technical', label: 'Especificaciones Técnicas', icon: Settings },
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
    showModal.value = true;
};

const openEditModal = (item) => {
    isEditing.value = true;
    itemId.value = item.id;
    activeSection.value = 'general';
    currentImage.value = item.imagen_url;
    
    // Map data to form
    form.nombre = item.nombre;
    form.placa = item.placa;
    form.numero_circulacion = item.numero_circulacion;
    form.anio = item.anio;
    form.kilometraje = item.kilometraje;
    form.tipo_combustible = item.tipo_combustible;
    form.capacidad_pasajeros = item.capacidad_pasajeros;
    form.categoria_id = item.categoria_id;
    form.departamento_id = item.departamento_id;
    form.ubicacion_id = item.ubicacion_id;
    form.marca_id = item.marca_id;
    form.modelo_id = item.modelo_id;
    form.color_id = item.color_id;
    form.fuente_id = item.fuente_id;
    form.proveedor_id = item.proveedor_id;
    form.responsable_id = item.responsable_id;
    form.estado_id = item.estado_id;
    form.fecha_adquisicion = item.fecha_adquisicion;
    form.valor_adquisicion = item.valor_adquisicion;
    form.vida_util_anios = item.vida_util_anios;
    form.valor_residual = item.valor_residual;
    form.codigo_inventario = item.codigo_inventario;
    form.numero_serie = item.numero_serie;
    form.imagen = null;

    showModal.value = true;
};

const closeModal = () => {
    showModal.value = false;
    form.clearErrors();
};

const submitForm = () => {
    if (isEditing.value) {
        // multipart/form-data via POST for Laravel
        form.post(route('vehiculos.update', itemId.value), {
            onSuccess: () => closeModal(),
            forceFormData: true,
        });
    } else {
        form.post(route('vehiculos.store'), {
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

const confirmDeleteVehiculo = (item) => {
    itemToDelete.value = item;
    showConfirmDelete.value = true;
};

const deleteItem = () => {
    if (itemToDelete.value) {
        router.delete(route('vehiculos.destroy', itemToDelete.value.id), {
            preserveScroll: true,
            onSuccess: () => {
                showConfirmDelete.value = false;
                itemToDelete.value = null;
            }
        });
    }
};

watch(() => form.categoria_id, (newCatId) => {
    const cat = props.categorias.find(c => c.id == newCatId);
    if (cat && !isEditing.value) {
        form.vida_util_anios = cat.vida_util_anios ?? 5;
    }
});

// Auto-calculate residual value (20%) when acquisition value changes
watch(() => form.valor_adquisicion, (newValue) => {
    if (!isEditing.value) {
        form.valor_residual = Number((newValue * 0.20).toFixed(2));
    }
});

onMounted(() => {
    if (props.filters?.edit_id) {
        const itemToEdit = props.vehiculos.data.find(v => v.id == props.filters.edit_id);
        if (itemToEdit) {
            openEditModal(itemToEdit);
        }
    }
});
</script>

<template>
    <Head title="Listado de Vehículos" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Control de Flota Vehicular</h2>
        </template>
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-2 sm:p-4">
            
             <!-- Actions Toolbar -->
            <div class="bg-gray-50 dark:bg-gray-900/50 p-3 rounded-xl border border-gray-100 dark:border-gray-700 mb-4 flex justify-between items-center">
                 <div>
                    <h3 class="text-sm font-bold text-gray-700 dark:text-gray-300 flex items-center gap-2">
                        <Car class="w-4 h-4 text-indigo-500" />
                        Listado de Unidades
                    </h3>
                 </div>
                 <button @click="openCreateModal" class="flex items-center gap-2 text-white bg-indigo-600 hover:bg-indigo-700 font-bold rounded-lg text-xs px-4 py-2 shadow-sm hover:shadow transition-all group">
                     <Plus class="w-4 h-4 group-hover:rotate-90 transition-transform" />
                     Registrar Vehículo
                 </button>
            </div>

            <div class="relative overflow-hidden shadow-sm rounded-xl border border-gray-100 dark:border-gray-700">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-[10px] text-gray-600 dark:text-gray-300 uppercase bg-gray-50/80 dark:bg-gray-700/80 backdrop-blur-sm border-b dark:border-gray-600">
                        <tr>
                            <th class="px-3 py-2.5 font-bold">Placa</th>
                            <th class="px-3 py-2.5 font-bold">Vehículo</th>
                            <th class="px-3 py-2.5 font-bold">Detalles Técnicos</th>
                            <th class="px-3 py-2.5 font-bold">Asignación</th>
                            <th class="px-3 py-2.5 text-right font-bold">Valor Diario</th>
                            <th class="px-3 py-2.5 text-center font-bold">Estado</th>
                            <th class="px-3 py-2.5 text-center font-bold">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 dark:divide-gray-700">
                        <tr v-for="vehiculo in vehiculos.data" :key="vehiculo.id" class="bg-white dark:bg-gray-800 hover:bg-gray-50/80 dark:hover:bg-gray-700/50 transition-all">
                            <td class="px-3 py-2">
                                <div class="px-2 py-1 bg-yellow-400 text-black font-bold font-mono rounded border-2 border-black text-center shadow-sm w-fit mx-auto md:mx-0">
                                    {{ vehiculo.placa }}
                                </div>
                            </td>
                            <td class="px-3 py-2">
                                <div class="flex items-center gap-3">
                                    <div v-if="vehiculo.imagen_url" class="w-10 h-10 rounded-lg overflow-hidden border border-gray-100 bg-gray-50">
                                        <img :src="vehiculo.imagen_url" class="w-full h-full object-cover" alt="">
                                    </div>
                                    <div v-else class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center">
                                        <Car class="w-5 h-5 text-gray-400" />
                                    </div>
                                    <div>
                                        <span class="font-bold text-gray-900 dark:text-white block">{{ vehiculo.nombre }}</span>
                                        <span class="text-[10px] text-gray-500 uppercase font-medium">{{ vehiculo.marca }} {{ vehiculo.modelo }} ({{ vehiculo.anio }})</span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-3 py-2 text-xs">
                                <div class="flex flex-col gap-0.5">
                                    <span class="flex items-center gap-1"><span class="w-1.5 h-1.5 rounded-full bg-gray-300"></span> {{ vehiculo.color }}</span>
                                    <span class="flex items-center gap-1 font-medium text-gray-400 capitalize"><Settings class="w-3 h-3" /> {{ vehiculo.tipo_combustible }}</span>
                                </div>
                            </td>
                            <td class="px-3 py-2 text-xs">
                                <div class="font-semibold text-gray-800 dark:text-gray-200">{{ vehiculo.departamento }}</div>
                                <div class="text-[10px] text-gray-500 italic">{{ vehiculo.responsable || 'Sin Asignar' }}</div>
                            </td>
                            <td class="px-3 py-2 text-right font-mono text-xs font-bold text-indigo-600">
                                {{ formatCurrency(vehiculo.depreciacion_mensual / 30) }}
                            </td>
                             <td class="px-3 py-2 text-center">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold tracking-tight uppercase" :class="[
                                    vehiculo.estado?.toLowerCase().includes('buen') ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-300' :
                                    vehiculo.estado?.toLowerCase().includes('mantenimiento') ? 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-300' :
                                    'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-300'
                                ]">
                                    {{ vehiculo.estado }}
                                </span>
                            </td>
                            <td class="px-3 py-2 text-center">
                                <div class="flex items-center justify-center gap-1">
                                    <Link :href="route('vehiculos.show', vehiculo.id)" class="p-1.5 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 dark:hover:bg-indigo-900/40 rounded-lg transition-all" title="Ver Detalles"><Info class="w-4 h-4" /></Link>
                                    <button @click="openEditModal(vehiculo)" class="p-1.5 text-gray-400 hover:text-emerald-600 hover:bg-emerald-50 dark:hover:bg-emerald-900/40 rounded-lg transition-all" title="Editar"><Settings class="w-4 h-4" /></button>
                                    <button @click="confirmDeleteVehiculo(vehiculo)" class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-900/40 rounded-lg transition-all" title="Eliminar"><X class="w-4 h-4" /></button>
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
                        <div class="p-3 bg-indigo-50 dark:bg-indigo-900/30 rounded-2xl">
                            <Car class="w-6 h-6 text-indigo-600 dark:text-indigo-400" />
                        </div>
                        <div>
                            <h2 class="text-xl font-extrabold text-gray-900 dark:text-white tracking-tight">
                                {{ isEditing ? 'Editar Vehículo' : 'Nuevo Vehículo' }}
                            </h2>
                            <p class="text-xs text-gray-500 font-medium uppercase tracking-wider">Gestión de Flota Vehicular</p>
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
                                        (section.id === 'general' && ['nombre', 'placa', 'numero_circulacion'].includes(k)) ||
                                        (section.id === 'technical' && ['anio', 'marca_id', 'modelo_id', 'color_id', 'tipo_combustible', 'kilometraje', 'capacidad_pasajeros', 'numero_serie'].includes(k)) ||
                                        (section.id === 'assignment' && ['departamento_id', 'ubicacion_id', 'responsable_id', 'estado_id', 'categoria_id'].includes(k)) ||
                                        (section.id === 'financial' && ['fecha_adquisicion', 'valor_adquisicion', 'vida_util_anios', 'valor_residual'].includes(k))
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
                                        <InputLabel value="Nombre Identificador del Vehículo" class="text-xs font-bold uppercase text-gray-400" />
                                        <TextInput 
                                            type="text" 
                                            v-model="form.nombre" 
                                            class="w-full border-gray-200 rounded-xl focus:ring-indigo-500" 
                                            placeholder="Ej: Camioneta Administrativa 01"
                                        />
                                        <InputError :message="form.errors.nombre" />
                                    </div>
                                    <div class="space-y-2">
                                        <InputLabel value="Número de Placa" class="text-xs font-bold uppercase text-gray-400" />
                                        <TextInput 
                                            type="text" 
                                            v-model="form.placa" 
                                            class="w-full border-gray-200 rounded-xl focus:ring-indigo-500 font-mono font-bold" 
                                            placeholder="Ej: M 123456"
                                        />
                                        <InputError :message="form.errors.placa" />
                                    </div>
                                    <div class="space-y-2">
                                        <InputLabel value="Número de Circulación" class="text-xs font-bold uppercase text-gray-400" />
                                        <TextInput 
                                            type="text" 
                                            v-model="form.numero_circulacion" 
                                            class="w-full border-gray-200 rounded-xl focus:ring-indigo-500" 
                                            placeholder="Ej: 00234567"
                                        />
                                        <InputError :message="form.errors.numero_circulacion" />
                                    </div>

                                    <div class="space-y-2">
                                        <InputLabel value="Categoría" class="text-xs font-bold uppercase text-gray-400" />
                                        <SearchSelect 
                                            v-model="form.categoria_id" 
                                            :options="categorias" 
                                            placeholder="Seleccione una categoría" 
                                            :error="form.errors.categoria_id"
                                        />
                                        <InputError :message="form.errors.categoria_id" />
                                    </div>
                                </div>

                                <!-- Multimedia Upload -->
                                <div class="space-y-4">
                                    <InputLabel value="Fotografía de la Unidad" class="text-xs font-bold uppercase text-gray-400" />
                                    <div 
                                        @dragover.prevent 
                                        @drop.prevent="handleFileDrop"
                                        class="relative group border-2 border-dashed border-gray-200 dark:border-gray-700 rounded-3xl p-8 transition-all hover:border-indigo-400 hover:bg-indigo-50/30 dark:hover:bg-indigo-900/10 flex flex-col items-center justify-center gap-4 overflow-hidden"
                                    >
                                        <div v-if="getPreviewUrl()" class="absolute inset-0">
                                            <img :src="getPreviewUrl()" class="w-full h-full object-cover" />
                                            <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-4">
                                                <button type="button" @click="form.imagen = null; currentImage = null" class="p-3 bg-red-500 text-white rounded-2xl hover:scale-110 transition-transform"><X class="w-6 h-6" /></button>
                                                <label class="p-3 bg-white text-gray-900 rounded-2xl hover:scale-110 transition-transform cursor-pointer"><Camera class="w-6 h-6" /><input type="file" @change="handleFileSelect" class="hidden" accept="image/*" /></label>
                                            </div>
                                        </div>
                                        <template v-else>
                                            <div class="p-4 bg-indigo-50 dark:bg-indigo-900/30 rounded-full group-hover:scale-110 transition-transform"><UploadCloud class="w-10 h-10 text-indigo-600 dark:text-indigo-400" /></div>
                                            <div class="text-center">
                                                <p class="text-sm font-bold text-gray-700 dark:text-gray-300">Arrastra una imagen o haz clic para subir</p>
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
                                        <InputLabel value="Marca" class="text-xs font-bold uppercase text-gray-400" />
                                        <SearchSelect 
                                            v-model="form.marca_id" 
                                            :options="marcas" 
                                            placeholder="Seleccionar Marca" 
                                            :error="form.errors.marca_id"
                                        />
                                        <InputError :message="form.errors.marca_id" />
                                    </div>
                                    <div class="space-y-2">
                                        <InputLabel value="Modelo" class="text-xs font-bold uppercase text-gray-400" />
                                        <SearchSelect 
                                            v-model="form.modelo_id" 
                                            :options="modelos" 
                                            placeholder="Seleccionar Modelo" 
                                            :error="form.errors.modelo_id"
                                        />
                                        <InputError :message="form.errors.modelo_id" />
                                    </div>
                                    <div class="space-y-2">
                                        <InputLabel value="Año" class="text-xs font-bold uppercase text-gray-400" />
                                        <TextInput type="number" v-model="form.anio" class="w-full border-gray-200 rounded-xl" />
                                        <InputError :message="form.errors.anio" />
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                    <div class="space-y-2">
                                        <InputLabel value="Color" class="text-xs font-bold uppercase text-gray-400" />
                                        <SearchSelect 
                                            v-model="form.color_id" 
                                            :options="colores" 
                                            placeholder="Seleccionar Color" 
                                            :error="form.errors.color_id"
                                        />
                                        <InputError :message="form.errors.color_id" />
                                    </div>
                                    <div class="space-y-2">
                                        <InputLabel value="Tipo de Combustible" class="text-xs font-bold uppercase text-gray-400" />
                                        <select v-model="form.tipo_combustible" class="w-full border-gray-200 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-xl focus:ring-indigo-500">
                                            <option value="">Seleccionar Combustible</option>
                                            <option value="Gasolina">Gasolina</option>
                                            <option value="Diesel">Diesel</option>
                                            <option value="Electrico">Eléctrico</option>
                                            <option value="Hibrido">Híbrido</option>
                                        </select>
                                        <InputError :message="form.errors.tipo_combustible" />
                                    </div>
                                    <div class="space-y-2">
                                        <InputLabel value="Pasajeros" class="text-xs font-bold uppercase text-gray-400" />
                                        <TextInput type="number" v-model="form.capacidad_pasajeros" class="w-full border-gray-200 rounded-xl" />
                                        <InputError :message="form.errors.capacidad_pasajeros" />
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="space-y-2">
                                        <InputLabel value="Kilometraje Actual" class="text-xs font-bold uppercase text-gray-400" />
                                        <TextInput type="number" v-model="form.kilometraje" class="w-full border-gray-200 rounded-xl" />
                                        <InputError :message="form.errors.kilometraje" />
                                    </div>
                                    <div class="space-y-2">
                                        <InputLabel value="Número de Serie/Chasis" class="text-xs font-bold uppercase text-gray-400" />
                                        <TextInput type="text" v-model="form.numero_serie" class="w-full border-gray-200 rounded-xl" />
                                        <InputError :message="form.errors.numero_serie" />
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
                                            placeholder="Seleccionar Departamento" 
                                            :error="form.errors.departamento_id"
                                        />
                                        <InputError :message="form.errors.departamento_id" />
                                    </div>
                                    <div class="space-y-2">
                                        <InputLabel value="Ubicación Física" class="text-xs font-bold uppercase text-gray-400" />
                                        <SearchSelect 
                                            v-model="form.ubicacion_id" 
                                            :options="ubicaciones" 
                                            placeholder="Seleccionar Ubicación" 
                                            :error="form.errors.ubicacion_id"
                                        />
                                        <InputError :message="form.errors.ubicacion_id" />
                                    </div>
                                    <div class="space-y-2">
                                        <InputLabel value="Responsable del Bien" class="text-xs font-bold uppercase text-gray-400" />
                                        <SearchSelect 
                                            v-model="form.responsable_id" 
                                            :options="responsables" 
                                            placeholder="Seleccionar Responsable" 
                                            :error="form.errors.responsable_id"
                                        />
                                        <InputError :message="form.errors.responsable_id" />
                                    </div>
                                    <div class="space-y-2">
                                        <InputLabel value="Estado del Activo" class="text-xs font-bold uppercase text-gray-400" />
                                        <SearchSelect 
                                            v-model="form.estado_id" 
                                            :options="estados" 
                                            placeholder="Seleccionar Estado" 
                                            :error="form.errors.estado_id"
                                        />
                                        <InputError :message="form.errors.estado_id" />
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
                                        <InputLabel value="Valor de Adquisición" class="text-xs font-bold uppercase text-gray-400" />
                                        <div class="relative">
                                            <span class="absolute left-4 top-3 text-gray-500 font-bold">C$</span>
                                            <TextInput type="number" step="0.01" v-model="form.valor_adquisicion" class="w-full pl-12 border-gray-200 rounded-xl focus:ring-indigo-500" />
                                        </div>
                                        <InputError :message="form.errors.valor_adquisicion" />
                                    </div>
                                    <div class="space-y-2">
                                        <div class="flex justify-between items-center mb-1">
                                            <InputLabel value="Vida Útil (Años)" class="text-xs font-bold uppercase text-gray-400" />
                                            <span class="text-[9px] font-black text-indigo-500 uppercase tracking-tighter bg-indigo-50 dark:bg-indigo-900/30 px-2 py-0.5 rounded-full">Automático (5 Años)</span>
                                        </div>
                                        <TextInput type="number" v-model="form.vida_util_anios" disabled class="w-full border-gray-200 rounded-xl bg-gray-50/50 dark:bg-gray-900/50 opacity-70 cursor-not-allowed font-bold" />
                                        <InputError :message="form.errors.vida_util_anios" />
                                    </div>
                                    <div class="space-y-2">
                                        <div class="flex justify-between items-center">
                                            <InputLabel value="Valor de Salvamento" class="text-xs font-bold uppercase text-gray-400" />
                                            <span class="text-[9px] font-black text-indigo-500 uppercase tracking-tighter bg-indigo-50 dark:bg-indigo-900/30 px-2 py-0.5 rounded-full">Automático (20%)</span>
                                        </div>
                                        <div class="relative">
                                            <span class="absolute left-4 top-3 text-gray-500 font-bold">C$</span>
                                            <TextInput type="number" step="0.01" v-model="form.valor_residual" class="w-full pl-12 border-gray-200 rounded-xl focus:ring-indigo-500" />
                                        </div>
                                        <InputError :message="form.errors.valor_residual" />
                                    </div>
                                </div>

                                <!-- Financial Summary Card -->
                                <div class="bg-indigo-50 dark:bg-indigo-900/10 rounded-2xl p-6 border border-indigo-100 dark:border-indigo-900/30">
                                    <h4 class="text-xs font-bold text-indigo-700 dark:text-indigo-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                                        <Search class="w-4 h-4" /> Proyección Financiera Estimada
                                    </h4>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <p class="text-[10px] text-gray-500 uppercase">Depreciación Anual</p>
                                            <p class="text-lg font-extrabold text-gray-900 dark:text-white">
                                                {{ formatCurrency((form.valor_adquisicion - form.valor_residual) / (form.vida_util_anios || 1)) }}
                                            </p>
                                        </div>
                                        <div>
                                            <p class="text-[10px] text-gray-500 uppercase">Depreciación Mensual</p>
                                            <p class="text-lg font-extrabold text-indigo-600">
                                                {{ formatCurrency(((form.valor_adquisicion - form.valor_residual) / (form.vida_util_anios || 1)) / 12) }}
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
                            <span class="text-[10px] font-bold uppercase tracking-tight">Faltan campos obligatorios</span>
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
                                    <span>{{ isEditing ? 'Actualizar Unidad' : 'Registrar Unidad' }}</span>
                                </template>
                            </div>
                        </PrimaryButton>
                    </div>
                </div>
            </div>
        </Modal>

        <!-- Confirmation Modal -->
        <ConfirmationModal :show="showConfirmDelete" @close="showConfirmDelete = false">
            <template #title>
                Eliminar Vehículo
            </template>
            <template #content>
                ¿Estás seguro de que deseas eliminar el vehículo <strong>{{ itemToDelete?.nombre }}</strong> con placa <strong>{{ itemToDelete?.placa }}</strong>? Esta acción no se puede deshacer y eliminará también el registro de activo fijo asociado.
            </template>
            <template #footer>
                <SecondaryButton @click="showConfirmDelete = false">
                    Cancelar
                </SecondaryButton>
                <PrimaryButton
                    class="ml-3 bg-red-600 hover:bg-red-700 focus:ring-red-500"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                    @click="deleteItem"
                >
                    Eliminar Vehículo
                </PrimaryButton>
            </template>
        </ConfirmationModal>
    </AuthenticatedLayout>
</template>

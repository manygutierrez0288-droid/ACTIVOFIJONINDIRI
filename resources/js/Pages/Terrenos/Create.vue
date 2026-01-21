<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';
import TextInput from '@/Components/TextInput.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import Checkbox from '@/Components/Checkbox.vue';
import { watch, computed } from 'vue';
import { 
    Package, 
    MapPin, 
    DollarSign, 
    Image as ImageIcon,
    ChevronLeft,
    Map
} from 'lucide-vue-next';

const props = defineProps({
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

const form = useForm({
    nombre: '',
    imagen: null,
    categoria_id: '',
    departamento_id: '',
    ubicacion_id: '',
    marca_id: '',
    modelo_id: '',
    fuente_id: '',
    proveedor_id: '',
    responsable_id: '',
    estado_id: '',
    fecha_adquisicion: '',
    valor_adquisicion: '',
    vida_util_anios: 0, // Terrenos no deprecian
    valor_residual: '',
    numero_serie: '',
    codigo_inventario: '',
    // Terreno specific fields
    numero_escritura: '',
    area_metros_cuadrados: '',
    frente_metros: '',
    fondo_metros: '',
    lindero_norte: '',
    lindero_sur: '',
    lindero_este: '',
    lindero_oeste: '',
    coordenadas_gps: '',
    uso_suelo: '',
    zonificacion: '',
    valor_catastral: '',
});

// Cálculo automático del área
const areaCalculada = computed(() => {
    const frente = parseFloat(form.frente_metros) || 0;
    const fondo = parseFloat(form.fondo_metros) || 0;
    if (frente > 0 && fondo > 0) {
        return (frente * fondo).toFixed(2);
    }
    return null;
});

// Auto-llenar área si se calculó
watch(areaCalculada, (newVal) => {
    if (newVal && !form.area_metros_cuadrados) {
        form.area_metros_cuadrados = newVal;
    }
});

// Valor residual = valor adquisición (terrenos no deprecian)
watch(() => form.valor_adquisicion, (newVal) => {
    form.valor_residual = newVal;
});

const submit = () => form.post(route('terrenos.store'));
</script>

<template>
    <Head title="Registrar Terreno" />
    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center gap-4">
                <Link :href="route('terrenos.index')" class="p-2 bg-white dark:bg-gray-800 rounded-lg shadow-sm hover:bg-gray-50 transition-colors">
                    <ChevronLeft class="w-5 h-5 text-gray-500" />
                </Link>
                <h2 class="font-bold text-2xl text-gray-900 dark:text-white leading-tight">Registrar Nuevo Terreno</h2>
            </div>
        </template>

        <div class="max-w-5xl mx-auto pb-12">
            <form @submit.prevent="submit" class="space-y-8">
                
                <!-- SECCIÓN 1: IDENTIFICACIÓN -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-50 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/50 flex items-center gap-3">
                        <div class="p-2 bg-indigo-100 dark:bg-indigo-900/30 rounded-lg text-indigo-600 dark:text-indigo-400">
                            <Package class="w-5 h-5" />
                        </div>
                        <h3 class="font-bold text-gray-900 dark:text-white">Identificación General</h3>
                    </div>
                    <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <InputLabel for="nombre" value="Nombre / Descripción del Terreno" class="text-xs font-bold uppercase text-gray-500 mb-1" />
                            <TextInput id="nombre" class="mt-1 block w-full rounded-xl border-gray-200 focus:ring-indigo-500" v-model="form.nombre" required placeholder="Ej: Terreno Lote 15 Zona Industrial" />
                            <InputError :message="form.errors.nombre" />
                        </div>
                        
                        <div>
                            <InputLabel for="imagen" value="Fotografía / Plano" class="text-xs font-bold uppercase text-gray-500 mb-1" />
                            <label class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 dark:border-gray-600 border-dashed rounded-xl hover:border-indigo-400 dark:hover:border-indigo-500 transition-colors cursor-pointer group">
                                <div class="space-y-1 text-center">
                                    <ImageIcon class="mx-auto h-12 w-12 text-gray-400 group-hover:text-indigo-500 transition-colors" />
                                    <div class="flex text-sm text-gray-600 dark:text-gray-400">
                                        <span class="relative cursor-pointer bg-white dark:bg-transparent rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none">
                                            Subir archivo
                                        </span>
                                    </div>
                                    <input type="file" id="imagen" class="sr-only" @input="form.imagen = $event.target.files[0]" />
                                </div>
                            </label>
                            <p v-if="form.imagen" class="mt-2 text-sm text-indigo-600 font-medium flex items-center gap-2">
                                <Package class="w-4 h-4" /> {{ form.imagen.name }}
                            </p>
                            <InputError :message="form.errors.imagen" />
                        </div>

                         <div class="flex flex-col justify-center bg-indigo-50 dark:bg-indigo-900/10 p-4 rounded-xl border border-indigo-100 dark:border-indigo-900/30">
                            <p class="text-sm text-indigo-800 dark:text-indigo-300 italic">
                                Los terrenos no se deprecian. El valor residual será igual al valor de adquisición.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- SECCIÓN 2: DATOS DEL TERRENO -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-50 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/50 flex items-center gap-3">
                        <div class="p-2 bg-emerald-100 dark:bg-emerald-900/30 rounded-lg text-emerald-600 dark:text-emerald-400">
                            <Map class="w-5 h-5" />
                        </div>
                        <h3 class="font-bold text-gray-900 dark:text-white">Datos Específicos del Terreno</h3>
                    </div>
                    <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <InputLabel for="numero_escritura" value="Número de Escritura" class="text-xs font-bold uppercase text-gray-500 mb-1" />
                            <TextInput id="numero_escritura" class="mt-1 block w-full rounded-xl border-gray-200 focus:ring-emerald-500 font-mono uppercase" v-model="form.numero_escritura" required placeholder="ESC-2024-001" />
                            <InputError :message="form.errors.numero_escritura" />
                        </div>
                        <div>
                            <InputLabel for="area_metros_cuadrados" value="Área Total (m²)" class="text-xs font-bold uppercase text-gray-500 mb-1" />
                            <TextInput id="area_metros_cuadrados" type="number" step="0.01" class="mt-1 block w-full rounded-xl border-gray-200 focus:ring-emerald-500 font-bold text-emerald-700" v-model="form.area_metros_cuadrados" required placeholder="0.00" />
                            <p v-if="areaCalculada" class="text-xs text-emerald-600 mt-1">Calculado: {{ areaCalculada }} m²</p>
                            <InputError :message="form.errors.area_metros_cuadrados" />
                        </div>
                        <div>
                            <InputLabel for="valor_catastral" value="Valor Catastral (C$)" class="text-xs font-bold uppercase text-gray-500 mb-1" />
                            <TextInput id="valor_catastral" type="number" step="0.01" class="mt-1 block w-full rounded-xl border-gray-200 focus:ring-emerald-500" v-model="form.valor_catastral" placeholder="0.00" />
                            <InputError :message="form.errors.valor_catastral" />
                        </div>
                        <div>
                            <InputLabel for="frente_metros" value="Frente (metros)" class="text-xs font-bold uppercase text-gray-500 mb-1" />
                            <TextInput id="frente_metros" type="number" step="0.01" class="mt-1 block w-full rounded-xl border-gray-200 focus:ring-emerald-500" v-model="form.frente_metros" placeholder="0.00" />
                            <InputError :message="form.errors.frente_metros" />
                        </div>
                        <div>
                            <InputLabel for="fondo_metros" value="Fondo (metros)" class="text-xs font-bold uppercase text-gray-500 mb-1" />
                            <TextInput id="fondo_metros" type="number" step="0.01" class="mt-1 block w-full rounded-xl border-gray-200 focus:ring-emerald-500" v-model="form.fondo_metros" placeholder="0.00" />
                            <InputError :message="form.errors.fondo_metros" />
                        </div>
                        <div>
                            <InputLabel for="coordenadas_gps" value="Coordenadas GPS" class="text-xs font-bold uppercase text-gray-500 mb-1" />
                            <TextInput id="coordenadas_gps" class="mt-1 block w-full rounded-xl border-gray-200 focus:ring-emerald-500 font-mono text-xs" v-model="form.coordenadas_gps" placeholder="12.1234, -86.5678" />
                            <InputError :message="form.errors.coordenadas_gps" />
                        </div>
                        <div>
                            <InputLabel for="uso_suelo" value="Uso de Suelo" class="text-xs font-bold uppercase text-gray-500 mb-1" />
                            <select id="uso_suelo" v-model="form.uso_suelo" class="mt-1 block w-full rounded-xl border-gray-200 dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-emerald-500">
                                <option value="">Seleccionar...</option>
                                <option value="Residencial">Residencial</option>
                                <option value="Comercial">Comercial</option>
                                <option value="Industrial">Industrial</option>
                                <option value="Agrícola">Agrícola</option>
                                <option value="Mixto">Mixto</option>
                            </select>
                            <InputError :message="form.errors.uso_suelo" />
                        </div>
                        <div class="md:col-span-2">
                            <InputLabel for="zonificacion" value="Zonificación" class="text-xs font-bold uppercase text-gray-500 mb-1" />
                            <TextInput id="zonificacion" class="mt-1 block w-full rounded-xl border-gray-200 focus:ring-emerald-500" v-model="form.zonificacion" placeholder="Ej: Zona R-2, Zona Industrial I-1" />
                            <InputError :message="form.errors.zonificacion" />
                        </div>
                    </div>
                </div>

                <!-- SECCIÓN 3: LINDEROS -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-50 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/50 flex items-center gap-3">
                        <div class="p-2 bg-purple-100 dark:bg-purple-900/30 rounded-lg text-purple-600 dark:text-purple-400">
                            <MapPin class="w-5 h-5" />
                        </div>
                        <h3 class="font-bold text-gray-900 dark:text-white">Linderos y Colindancias</h3>
                    </div>
                    <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <InputLabel for="lindero_norte" value="Lindero Norte" class="text-xs font-bold uppercase text-gray-500 mb-1" />
                            <textarea id="lindero_norte" v-model="form.lindero_norte" rows="2" class="mt-1 block w-full rounded-xl border-gray-200 dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-purple-500" placeholder="Descripción del lindero norte"></textarea>
                            <InputError :message="form.errors.lindero_norte" />
                        </div>
                        <div>
                            <InputLabel for="lindero_sur" value="Lindero Sur" class="text-xs font-bold uppercase text-gray-500 mb-1" />
                            <textarea id="lindero_sur" v-model="form.lindero_sur" rows="2" class="mt-1 block w-full rounded-xl border-gray-200 dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-purple-500" placeholder="Descripción del lindero sur"></textarea>
                            <InputError :message="form.errors.lindero_sur" />
                        </div>
                        <div>
                            <InputLabel for="lindero_este" value="Lindero Este" class="text-xs font-bold uppercase text-gray-500 mb-1" />
                            <textarea id="lindero_este" v-model="form.lindero_este" rows="2" class="mt-1 block w-full rounded-xl border-gray-200 dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-purple-500" placeholder="Descripción del lindero este"></textarea>
                            <InputError :message="form.errors.lindero_este" />
                        </div>
                        <div>
                            <InputLabel for="lindero_oeste" value="Lindero Oeste" class="text-xs font-bold uppercase text-gray-500 mb-1" />
                            <textarea id="lindero_oeste" v-model="form.lindero_oeste" rows="2" class="mt-1 block w-full rounded-xl border-gray-200 dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-purple-500" placeholder="Descripción del lindero oeste"></textarea>
                            <InputError :message="form.errors.lindero_oeste" />
                        </div>
                    </div>
                </div>

                <!-- SECCIÓN 4: UBICACIÓN Y ESTADO -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-50 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/50 flex items-center gap-3">
                        <div class="p-2 bg-blue-100 dark:bg-blue-900/30 rounded-lg text-blue-600 dark:text-blue-400">
                            <MapPin class="w-5 h-5" />
                        </div>
                        <h3 class="font-bold text-gray-900 dark:text-white">Ubicación Administrativa</h3>
                    </div>
                    <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <InputLabel for="categoria_id" value="Categoría" class="text-xs font-bold uppercase text-gray-500 mb-1" />
                            <select id="categoria_id" v-model="form.categoria_id" class="mt-1 block w-full rounded-xl border-gray-200 dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-blue-500" required>
                                <option value="">Seleccionar...</option>
                                <option v-for="cat in categorias" :key="cat.id" :value="cat.id">{{ cat.nombre }}</option>
                            </select>
                            <InputError :message="form.errors.categoria_id" />
                        </div>
                        <div>
                            <InputLabel for="departamento_id" value="Departamento" class="text-xs font-bold uppercase text-gray-500 mb-1" />
                            <select id="departamento_id" v-model="form.departamento_id" class="mt-1 block w-full rounded-xl border-gray-200 dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-blue-500" required>
                                <option value="">Seleccionar...</option>
                                <option v-for="dep in departamentos" :key="dep.id" :value="dep.id">{{ dep.nombre }}</option>
                            </select>
                            <InputError :message="form.errors.departamento_id" />
                        </div>
                        <div>
                            <InputLabel for="ubicacion_id" value="Ubicación" class="text-xs font-bold uppercase text-gray-500 mb-1" />
                            <select id="ubicacion_id" v-model="form.ubicacion_id" class="mt-1 block w-full rounded-xl border-gray-200 dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-blue-500" required>
                                <option value="">Seleccionar...</option>
                                <option v-for="ubi in ubicaciones" :key="ubi.id" :value="ubi.id">{{ ubi.nombre }}</option>
                            </select>
                            <InputError :message="form.errors.ubicacion_id" />
                        </div>
                        <div>
                            <InputLabel for="estado_id" value="Estado" class="text-xs font-bold uppercase text-gray-500 mb-1" />
                            <select id="estado_id" v-model="form.estado_id" class="mt-1 block w-full rounded-xl border-gray-200 dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-blue-500" required>
                                <option value="">Seleccionar...</option>
                                <option v-for="est in estados" :key="est.id" :value="est.id">{{ est.nombre }}</option>
                            </select>
                            <InputError :message="form.errors.estado_id" />
                        </div>
                        <div class="md:col-span-2">
                            <InputLabel for="responsable_id" value="Responsable" class="text-xs font-bold uppercase text-gray-500 mb-1" />
                            <select id="responsable_id" v-model="form.responsable_id" class="mt-1 block w-full rounded-xl border-gray-200 dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-blue-500">
                                <option value="">Sin asignar...</option>
                                <option v-for="res in responsables" :key="res.id" :value="res.id">{{ res.nombre }}</option>
                            </select>
                            <InputError :message="form.errors.responsable_id" />
                        </div>
                    </div>
                </div>

                <!-- SECCIÓN 5: DATOS FINANCIEROS -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-50 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/50 flex items-center gap-3">
                        <div class="p-2 bg-amber-100 dark:bg-amber-900/30 rounded-lg text-amber-600 dark:text-amber-400">
                            <DollarSign class="w-5 h-5" />
                        </div>
                        <h3 class="font-bold text-gray-900 dark:text-white">Adquisición y Costos</h3>
                    </div>
                    <div class="p-6 grid grid-cols-1 md:grid-cols-4 gap-6">
                        <div class="md:col-span-2">
                            <InputLabel for="proveedor_id" value="Vendedor / Propietario Anterior" class="text-xs font-bold uppercase text-gray-500 mb-1" />
                            <select id="proveedor_id" v-model="form.proveedor_id" class="mt-1 block w-full rounded-xl border-gray-200 dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-amber-500">
                                <option value="">Seleccionar...</option>
                                <option v-for="prov in proveedores" :key="prov.id" :value="prov.id">{{ prov.nombre }}</option>
                            </select>
                            <InputError :message="form.errors.proveedor_id" />
                        </div>
                        <div class="md:col-span-2">
                            <InputLabel for="fuente_id" value="Fuente de Financiamiento" class="text-xs font-bold uppercase text-gray-500 mb-1" />
                            <select id="fuente_id" v-model="form.fuente_id" class="mt-1 block w-full rounded-xl border-gray-200 dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-amber-500">
                                <option value="">Seleccionar...</option>
                                <option v-for="fue in fuentes" :key="fue.id" :value="fue.id">{{ fue.nombre }}</option>
                            </select>
                            <InputError :message="form.errors.fuente_id" />
                        </div>

                        <div>
                            <InputLabel for="fecha_adquisicion" value="Fecha de Adquisición" class="text-xs font-bold uppercase text-gray-500 mb-1" />
                            <TextInput id="fecha_adquisicion" type="date" class="mt-1 block w-full rounded-xl border-gray-200 focus:ring-amber-500" v-model="form.fecha_adquisicion" required />
                            <InputError :message="form.errors.fecha_adquisicion" />
                        </div>
                        <div>
                            <InputLabel for="valor_adquisicion" value="Valor Adquisición (C$)" class="text-xs font-bold uppercase text-gray-500 mb-1" />
                            <TextInput id="valor_adquisicion" type="number" step="0.01" class="mt-1 block w-full rounded-xl border-gray-200 focus:ring-amber-500 font-bold" v-model="form.valor_adquisicion" required placeholder="0.00" />
                            <InputError :message="form.errors.valor_adquisicion" />
                        </div>
                        <div class="md:col-span-2">
                            <InputLabel for="codigo_inventario" value="Código de Inventario (Opcional)" class="text-xs font-bold uppercase text-gray-500 mb-1" />
                            <TextInput id="codigo_inventario" class="mt-1 block w-full rounded-xl border-gray-200 focus:ring-amber-500 font-mono uppercase" v-model="form.codigo_inventario" placeholder="Se generará automáticamente si se deja vacío" />
                            <InputError :message="form.errors.codigo_inventario" />
                        </div>
                    </div>
                </div>

                <!-- ACCIONES -->
                <div class="flex items-center justify-end gap-4 p-6 bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700">
                    <Link :href="route('terrenos.index')" class="text-sm font-bold text-gray-500 hover:text-gray-700 transition-colors uppercase tracking-widest">
                        Cancelar
                    </Link>
                    <PrimaryButton 
                        class="bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold py-3 px-8 rounded-xl shadow-lg shadow-indigo-200 dark:shadow-none transition-all active:scale-95" 
                        :disabled="form.processing"
                    >
                        {{ form.processing ? 'Registrando...' : 'REGISTRAR TERRENO' }}
                    </PrimaryButton>
                </div>

            </form>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
input:focus, select:focus, textarea:focus {
    transform: translateY(-1px);
    transition: all 0.2s ease;
}
</style>

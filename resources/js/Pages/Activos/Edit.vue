<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';
import TextInput from '@/Components/TextInput.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { onMounted } from 'vue';

const props = defineProps({
    activo: Object,
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
    nombre: props.activo.nombre || '',
    categoria_id: props.activo.categoria_id || '',
    departamento_id: props.activo.departamento_id || '',
    ubicacion_id: props.activo.ubicacion_id || '',
    marca_id: props.activo.marca_id || '',
    modelo_id: props.activo.modelo_id || '',
    color_id: props.activo.color_id || '',
    fuente_id: props.activo.fuente_id || '',
    proveedor_id: props.activo.proveedor_id || '',
    responsable_id: props.activo.responsable_id || '',
    estado_id: props.activo.estado_id || '',
    fecha_adquisicion: props.activo.fecha_adquisicion ? props.activo.fecha_adquisicion.substring(0, 10) : '',
    valor_adquisicion: props.activo.valor_adquisicion || '',
    vida_util_anios: props.activo.vida_util_anios || '',
    valor_residual: props.activo.valor_residual || '',
});

const submit = () => form.put(route('activos.update', props.activo.id));
</script>

<template>
    <Head title="Editar Activo Fijo" />
    <AuthenticatedLayout>
        <template #header><h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Editar Activo Fijo</h2></template>
        <div class="py-12"><div class="max-w-7xl mx-auto sm:px-6 lg:px-8"><div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
            <form @submit.prevent="submit" class="space-y-6">
                <!-- Información Principal -->
                <div class="border-b pb-4 dark:border-gray-700">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Información General</h3>
                    <div class="mt-4">
                        <InputLabel for="nombre" value="Nombre del Activo" />
                        <TextInput id="nombre" class="mt-1 block w-full" v-model="form.nombre" required />
                        <InputError :message="form.errors.nombre" />
                    </div>
                </div>

                <!-- Clasificación y Ubicación -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <InputLabel for="categoria_id" value="Categoría" />
                        <select id="categoria_id" v-model="form.categoria_id" class="mt-1 block w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>
                            <option value="">Seleccionar...</option>
                            <option v-for="cat in categorias" :key="cat.id" :value="cat.id">{{ cat.nombre }}</option>
                        </select>
                        <InputError :message="form.errors.categoria_id" />
                    </div>
                    <div>
                        <InputLabel for="departamento_id" value="Departamento" />
                        <select id="departamento_id" v-model="form.departamento_id" class="mt-1 block w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>
                            <option value="">Seleccionar...</option>
                            <option v-for="dep in departamentos" :key="dep.id" :value="dep.id">{{ dep.nombre }}</option>
                        </select>
                        <InputError :message="form.errors.departamento_id" />
                    </div>
                    <div>
                        <InputLabel for="ubicacion_id" value="Ubicación" />
                        <select id="ubicacion_id" v-model="form.ubicacion_id" class="mt-1 block w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>
                            <option value="">Seleccionar...</option>
                            <option v-for="ubi in ubicaciones" :key="ubi.id" :value="ubi.id">{{ ubi.nombre }}</option>
                        </select>
                        <InputError :message="form.errors.ubicacion_id" />
                    </div>
                </div>

                <!-- Especificaciones Técnicas -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <div>
                        <InputLabel for="marca_id" value="Marca" />
                        <select id="marca_id" v-model="form.marca_id" class="mt-1 block w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            <option value="">Seleccionar...</option>
                            <option v-for="marca in marcas" :key="marca.id" :value="marca.id">{{ marca.nombre }}</option>
                        </select>
                        <InputError :message="form.errors.marca_id" />
                    </div>
                    <div>
                        <InputLabel for="modelo_id" value="Modelo" />
                        <select id="modelo_id" v-model="form.modelo_id" class="mt-1 block w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            <option value="">Seleccionar...</option>
                            <option v-for="mod in modelos" :key="mod.id" :value="mod.id">{{ mod.nombre }}</option>
                        </select>
                        <InputError :message="form.errors.modelo_id" />
                    </div>
                    <div>
                        <InputLabel for="color_id" value="Color" />
                        <select id="color_id" v-model="form.color_id" class="mt-1 block w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            <option value="">Seleccionar...</option>
                            <option v-for="col in colores" :key="col.id" :value="col.id">{{ col.nombre }}</option>
                        </select>
                        <InputError :message="form.errors.color_id" />
                    </div>
                    <div>
                        <InputLabel for="estado_id" value="Estado" />
                        <select id="estado_id" v-model="form.estado_id" class="mt-1 block w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>
                            <option value="">Seleccionar...</option>
                            <option v-for="est in estados" :key="est.id" :value="est.id">{{ est.nombre }}</option>
                        </select>
                        <InputError :message="form.errors.estado_id" />
                    </div>
                </div>

                <!-- Adquisición y Responsabilidad -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <InputLabel for="fuente_id" value="Fuente de Financiamiento" />
                        <select id="fuente_id" v-model="form.fuente_id" class="mt-1 block w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            <option value="">Seleccionar...</option>
                            <option v-for="fue in fuentes" :key="fue.id" :value="fue.id">{{ fue.nombre }}</option>
                        </select>
                        <InputError :message="form.errors.fuente_id" />
                    </div>
                    <div>
                        <InputLabel for="proveedor_id" value="Proveedor" />
                        <select id="proveedor_id" v-model="form.proveedor_id" class="mt-1 block w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            <option value="">Seleccionar...</option>
                            <option v-for="prov in proveedores" :key="prov.id" :value="prov.id">{{ prov.nombre }}</option>
                        </select>
                        <InputError :message="form.errors.proveedor_id" />
                    </div>
                    <div>
                        <InputLabel for="responsable_id" value="Responsable" />
                        <select id="responsable_id" v-model="form.responsable_id" class="mt-1 block w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            <option value="">Seleccionar...</option>
                            <option v-for="res in responsables" :key="res.id" :value="res.id">{{ res.nombre }}</option>
                        </select>
                        <InputError :message="form.errors.responsable_id" />
                    </div>
                </div>

                <!-- Valores Contables -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <div>
                        <InputLabel for="fecha_adquisicion" value="Fecha Adquisición" />
                        <TextInput id="fecha_adquisicion" type="date" class="mt-1 block w-full" v-model="form.fecha_adquisicion" />
                        <InputError :message="form.errors.fecha_adquisicion" />
                    </div>
                    <div>
                        <InputLabel for="valor_adquisicion" value="Valor Adquisición" />
                        <TextInput id="valor_adquisicion" type="number" step="0.01" class="mt-1 block w-full" v-model="form.valor_adquisicion" />
                        <InputError :message="form.errors.valor_adquisicion" />
                    </div>
                    <div>
                        <InputLabel for="vida_util_anios" value="Vida Útil (Años)" />
                        <TextInput id="vida_util_anios" type="number" class="mt-1 block w-full" v-model="form.vida_util_anios" />
                        <InputError :message="form.errors.vida_util_anios" />
                    </div>
                    <div>
                        <InputLabel for="valor_residual" value="Valor Residual" />
                        <TextInput id="valor_residual" type="number" step="0.01" class="mt-1 block w-full" v-model="form.valor_residual" />
                        <InputError :message="form.errors.valor_residual" />
                    </div>
                </div>

                <div class="flex justify-end mt-6 pt-6 border-t dark:border-gray-700">
                    <Link :href="route('activos.index')" class="mr-4 inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300">Cancelar</Link>
                    <PrimaryButton :disabled="form.processing">Actualizar Activo</PrimaryButton>
                </div>
            </form>
        </div></div></div>
    </AuthenticatedLayout>
</template>

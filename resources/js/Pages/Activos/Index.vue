<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

const props = defineProps({
    activos: Object,
    filters: Object,
    categorias: Array,
    departamentos: Array,
});

const search = ref(props.filters?.search || '');
const categoriaFilter = ref(props.filters?.categoria || '');
const departamentoFilter = ref(props.filters?.departamento || '');

const applyFilters = () => {
    router.get(route('activos.index'), {
        search: search.value,
        categoria: categoriaFilter.value,
        departamento: departamentoFilter.value,
    }, { preserveState: true, replace: true });
};

const deleteItem = (id) => {
    if (confirm('¿Estás seguro de eliminar este activo?')) {
        router.delete(route('activos.destroy', id));
    }
};
</script>

<template>
    <Head title="Activos Fijos" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Activos Fijos</h2>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <!-- Filters -->
                    <div class="flex flex-wrap gap-4 mb-6">
                        <input v-model="search" @input="applyFilters" type="text" placeholder="Buscar por nombre..." class="rounded-lg border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white px-4 py-2">
                        <select v-model="categoriaFilter" @change="applyFilters" class="rounded-lg border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white px-4 py-2">
                            <option value="">Todas las categorías</option>
                            <option v-for="cat in categorias" :key="cat.id" :value="cat.id">{{ cat.nombre }}</option>
                        </select>
                        <select v-model="departamentoFilter" @change="applyFilters" class="rounded-lg border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white px-4 py-2">
                            <option value="">Todos los departamentos</option>
                            <option v-for="dep in departamentos" :key="dep.id" :value="dep.id">{{ dep.nombre }}</option>
                        </select>
                        <Link :href="route('activos.create')" class="ml-auto text-white bg-blue-700 hover:bg-blue-800 font-medium rounded-lg text-sm px-5 py-2.5">Nuevo Activo</Link>
                    </div>

                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th class="px-6 py-3">Nombre</th>
                                    <th class="px-6 py-3">Categoría</th>
                                    <th class="px-6 py-3">Departamento</th>
                                    <th class="px-6 py-3">Ubicación</th>
                                    <th class="px-6 py-3">Estado</th>
                                    <th class="px-6 py-3">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="activo in activos.data" :key="activo.id" class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                    <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">{{ activo.nombre }}</td>
                                    <td class="px-6 py-4">{{ activo.categoria }}</td>
                                    <td class="px-6 py-4">{{ activo.departamento }}</td>
                                    <td class="px-6 py-4">{{ activo.ubicacion }}</td>
                                    <td class="px-6 py-4">
                                        <span class="px-2 py-1 text-xs rounded" :class="activo.estado === 'activo' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'">{{ activo.estado }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <Link :href="route('activos.edit', activo.id)" class="text-blue-600 hover:underline mr-3">Editar</Link>
                                        <button @click="deleteItem(activo.id)" class="text-red-600 hover:underline">Eliminar</button>
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

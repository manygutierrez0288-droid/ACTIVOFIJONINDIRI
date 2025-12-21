<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';

defineProps({ colores: Object });

const deleteItem = (id) => {
    if (confirm('¿Estás seguro de eliminar este color?')) {
        router.delete(route('colores.destroy', id));
    }
};
</script>

<template>
    <Head title="Colores" />
    <AuthenticatedLayout>
        <template #header><h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Colores</h2></template>
        <div class="py-12"><div class="max-w-7xl mx-auto sm:px-6 lg:px-8"><div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
            <div class="flex justify-end mb-4"><Link :href="route('colores.create')" class="text-white bg-blue-700 hover:bg-blue-800 font-medium rounded-lg text-sm px-5 py-2.5">Nuevo</Link></div>
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs uppercase bg-gray-50 dark:bg-gray-700"><tr><th class="px-6 py-3">Nombre</th><th class="px-6 py-3">Muestra</th><th class="px-6 py-3">Acciones</th></tr></thead>
                <tbody>
                    <tr v-for="item in colores.data" :key="item.id" class="bg-white border-b dark:bg-gray-800">
                        <td class="px-6 py-4">{{ item.nombre }}</td>
                        <td class="px-6 py-4">
                            <div :style="{ backgroundColor: item.hex_code || item.nombre }" class="w-6 h-6 rounded border border-gray-300"></div>
                        </td>
                        <td class="px-6 py-4"><Link :href="route('colores.edit', item.id)" class="text-blue-600 hover:underline mr-3">Editar</Link><button @click="deleteItem(item.id)" class="text-red-600 hover:underline">Eliminar</button></td>
                    </tr>
                </tbody>
            </table>
        </div></div></div>
    </AuthenticatedLayout>
</template>

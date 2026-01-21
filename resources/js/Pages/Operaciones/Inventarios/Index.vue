<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { ClipboardList, Plus, Calendar, User, ChevronRight, CheckCircle2, Clock } from 'lucide-vue-next';

const props = defineProps({
    inventarios: Array,
});
</script>

<template>
    <Head title="Inventario Físico" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Inventario Físico</h2>
                <Link :href="route('inventarios.create')" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <Plus class="w-4 h-4 mr-2" />
                    Nueva Sesión
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        
                        <div v-if="inventarios.length === 0" class="text-center py-12">
                            <ClipboardList class="w-16 h-16 text-gray-300 mx-auto mb-4" />
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">No hay sesiones de inventario</h3>
                            <p class="text-gray-500 dark:text-gray-400">Inicie una nueva sesión para comenzar la toma física de activos.</p>
                        </div>

                        <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <div v-for="inv in inventarios" :key="inv.id" class="border border-gray-200 dark:border-gray-700 rounded-xl p-5 hover:shadow-lg transition-shadow bg-gray-50/50 dark:bg-gray-900/30 group">
                                <div class="flex justify-between items-start mb-4">
                                    <div class="p-2 bg-white dark:bg-gray-800 rounded-lg shadow-sm">
                                        <ClipboardList class="w-6 h-6 text-indigo-600" />
                                    </div>
                                    <span :class="[
                                        'px-2 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider',
                                        inv.estado === 'abierto' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700'
                                    ]">
                                        {{ inv.estado }}
                                    </span>
                                </div>
                                
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2 group-hover:text-indigo-600 transition-colors">{{ inv.nombre }}</h3>
                                
                                <div class="space-y-2 text-sm text-gray-500 dark:text-gray-400">
                                    <div class="flex items-center">
                                        <Calendar class="w-4 h-4 mr-2" />
                                        <span>Inicio: {{ new Date(inv.fecha_inicio).toLocaleDateString() }}</span>
                                    </div>
                                    <div class="flex items-center">
                                        <User class="w-4 h-4 mr-2" />
                                        <span>Iniciado por: {{ inv.user.name }}</span>
                                    </div>
                                </div>

                                <div class="mt-6 flex justify-end border-t border-gray-100 dark:border-gray-700 pt-4">
                                    <Link :href="route('inventarios.show', inv.id)" class="inline-flex items-center text-indigo-600 font-bold text-sm hover:underline">
                                        Gestionar Inventario
                                        <ChevronRight class="w-4 h-4 ml-1" />
                                    </Link>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

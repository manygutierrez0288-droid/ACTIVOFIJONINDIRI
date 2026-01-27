<script setup>
import { Head, usePage } from '@inertiajs/vue3';
import { onMounted, computed } from 'vue';

const props = defineProps({
    mantenimiento: Object,
});

const page = usePage();

onMounted(() => {
    // Automatically open print dialog when loaded
    setTimeout(() => {
        window.print();
    }, 500);
});

const formatCurrency = (value) => {
    return new Intl.NumberFormat('es-NI', {
        style: 'currency',
        currency: 'NIO',
    }).format(value);
};

const formatDate = (dateString) => {
    if (!dateString) return 'N/A';
    const date = new Date(dateString);
    return date.toLocaleDateString('es-ES', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric'
    });
};
</script>

<template>
    <Head :title="`Ficha de Mantenimiento - ${mantenimiento.id}`" />

    <div class="bg-white min-h-screen p-8 max-w-4xl mx-auto">
        <!-- Header -->
        <div class="flex items-center justify-between border-b-2 border-gray-800 pb-4 mb-8">
            <div class="w-32">
                <img src="/images/logo_nindiri.png" alt="Logo Alcaldía" class="w-full h-auto" />
            </div>
            <div class="text-center flex-1">
                <h1 class="text-2xl font-bold uppercase tracking-wide">Alcaldía Municipal de Nindirí</h1>
                <p class="text-lg font-medium text-gray-700">Departamento de Activos Fijos</p>
                <h2 class="text-xl font-bold mt-2 uppercase underline decoration-2 underline-offset-4">Ficha de Mantenimiento de Activo</h2>
            </div>
            <div class="w-32 text-right">
                <p class="text-xs font-bold uppercase">Folio No.</p>
                <p class="text-lg font-mono font-bold text-red-600">#MANT-{{ mantenimiento.id }}</p>
            </div>
        </div>

        <!-- Main Content -->
        <div class="space-y-6">
            <!-- Asset Info -->
            <div class="border border-gray-300">
                <div class="bg-gray-100 p-2 border-b border-gray-300 font-bold uppercase text-center text-sm">
                    I. Datos del Activo
                </div>
                <div class="grid grid-cols-2 text-sm">
                    <div class="p-2 border-r border-b border-gray-300"><span class="font-bold">Código Inventario:</span> {{ mantenimiento.activo_fijo.codigo_inventario || 'N/A' }}</div>
                    <div class="p-2 border-b border-gray-300"><span class="font-bold">Categoría:</span> {{ mantenimiento.activo_fijo.categoria?.nombre || 'N/A' }}</div>
                    
                    <div class="p-2 border-r border-gray-300"><span class="font-bold">Nombre del Bien:</span> {{ mantenimiento.activo_fijo.nombre }}</div>
                    <div class="p-2"><span class="font-bold">Estado del Activo:</span> {{ mantenimiento.activo_fijo.estado_nombre || 'N/A' }}</div>
                </div>
            </div>

            <!-- Maintenance Detail -->
            <div class="border border-gray-300">
                <div class="bg-gray-100 p-2 border-b border-gray-300 font-bold uppercase text-center text-sm">
                    II. Detalle del Mantenimiento
                </div>
                <div class="grid grid-cols-2 text-sm">
                    <div class="p-2 border-r border-b border-gray-300"><span class="font-bold">Fecha Realizada/Programada:</span><br>{{ formatDate(mantenimiento.fecha) }}</div>
                    <div class="p-2 border-b border-gray-300"><span class="font-bold">Estado del Mantenimiento:</span><br>{{ mantenimiento.estado?.nombre || 'N/A' }}</div>
                    
                    <div class="p-2 border-r border-b border-gray-300"><span class="font-bold">Técnico Asignado:</span><br>{{ mantenimiento.tecnico?.nombre || 'N/A' }}</div>
                    <div class="p-2 border-b border-gray-300"><span class="font-bold">Proveedor / Taller:</span><br>{{ mantenimiento.proveedor?.nombre || 'N/A' }}</div>
                    
                    <div class="p-2 border-r border-gray-300 col-span-1"><span class="font-bold">Costo del Servicio:</span><br>{{ formatCurrency(mantenimiento.costo) }}</div>
                    <div class="p-2 col-span-1 border-l border-gray-300"><span class="font-bold">Tipo:</span><br>{{ mantenimiento.costo > 0 ? 'CORRECTIVO / EXTERNO' : 'PREVENTIVO / INTERNO' }}</div>
                </div>
                <div class="p-4 border-t border-gray-300 text-sm">
                    <span class="font-bold uppercase block mb-2 underline">Descripción de los Trabajos:</span>
                    <p class="whitespace-pre-wrap leading-relaxed">{{ mantenimiento.descripcion }}</p>
                </div>
            </div>

            <!-- Observations -->
            <div class="border border-gray-300 min-h-[100px]">
                <div class="bg-gray-100 p-2 border-b border-gray-300 font-bold uppercase text-center text-sm">
                    III. Observaciones y Recomendaciones
                </div>
                <div class="p-4 text-sm italic text-gray-400">
                    Espacio para anotaciones adicionales del responsable técnico...
                </div>
            </div>

            <!-- Signatures -->
             <div class="mt-16 pt-8">
                <div class="flex justify-between text-center">
                    <div class="w-1/3">
                        <div class="border-t border-black mb-2 mx-8"></div>
                        <p class="font-bold text-sm">Técnico Responsable</p>
                        <p class="text-[10px] text-gray-500 uppercase">Firma y Sello</p>
                    </div>
                    <div class="w-1/3">
                        <div class="border-t border-black mb-2 mx-8"></div>
                        <p class="font-bold text-sm">Autorizado Por</p>
                        <p class="text-[10px] text-gray-500 uppercase">Director Administrativo</p>
                    </div>
                    <div class="w-1/3">
                        <div class="border-t border-black mb-2 mx-8"></div>
                        <p class="font-bold text-sm">Control de Activos</p>
                        <p class="text-[10px] text-gray-500 uppercase">Validación de Sistema</p>
                    </div>
                </div>
            </div>

        </div>
        
        <!-- Footer -->
        <div class="text-center text-xs text-gray-400 mt-12 border-t pt-4">
            <p>Este documento certifica las labores de mantenimiento realizadas al bien municipal descrito.</p>
            <p>Generado por Sistema SIAFNIN el {{ new Date().toLocaleString() }}</p>
        </div>
    </div>
</template>

<style>
@media print {
    @page { margin: 0; size: letter portrait; }
    body { background: white; margin: 0.5cm; }
    nav, header, footer, .no-print { display: none !important; }
}
</style>

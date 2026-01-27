<script setup>
import { Head, usePage } from '@inertiajs/vue3';
import { onMounted, computed } from 'vue';

const props = defineProps({
    activo: Object,
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

const qrCodeUrl = computed(() => {
    const origin = page.props.app_url || (typeof window !== 'undefined' ? window.location.origin : '');
    const id = props.activo?.id ?? '';
    return `https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=${encodeURIComponent(origin + '/activos/' + id)}`;
});
</script>

<template>
    <Head :title="`Ficha Técnica - ${activo.id}`" />

    <div class="bg-white min-h-screen p-8 max-w-4xl mx-auto">
        <!-- Header -->
        <div class="flex items-center justify-between border-b-2 border-gray-800 pb-4 mb-8">
            <div class="w-32">
                <img src="/images/logo_nindiri.png" alt="Logo Alcaldía" class="w-full h-auto" />
            </div>
            <div class="text-center flex-1">
                <h1 class="text-2xl font-bold uppercase tracking-wide">Alcaldía Municipal de Nindirí</h1>
                <p class="text-lg font-medium text-gray-700">Departamento de Activos Fijos</p>
                <h2 class="text-xl font-bold mt-2 uppercase underline decoration-2 underline-offset-4">Ficha Técnica de Activo Fijo</h2>
            </div>
            <div class="flex flex-col items-center justify-center p-2 border-2 border-gray-200 rounded-lg">
                <img :src="qrCodeUrl" alt="QR Code" class="w-24 h-24" />
                <p class="text-[10px] uppercase font-bold mt-1">Escanear Detalle</p>
            </div>
        </div>

        <!-- Main Content -->
        <div class="space-y-6">
            <!-- Identification -->
            <div class="border border-gray-300">
                <div class="bg-gray-100 p-2 border-b border-gray-300 font-bold uppercase text-center text-sm">
                    I. Datos de Identificación
                </div>
                <div class="flex border-b border-gray-300">
                    <div class="flex-1 grid grid-cols-2 text-sm">
                        <div class="p-2 border-r border-b border-gray-300"><span class="font-bold">Código Inventario:</span> <span class="text-indigo-700 font-mono font-bold">{{ activo.codigo_inventario || 'N/A' }}</span></div>
                        <div class="p-2 border-b border-gray-300"><span class="font-bold">Categoría:</span> {{ activo.categoria }}</div>
                        
                        <div class="p-2 border-r border-b border-gray-300"><span class="font-bold">Nombre del Bien:</span> {{ activo.nombre }}</div>
                        <div class="p-2 border-b border-gray-300"><span class="font-bold">Estado Actual:</span> {{ activo.estado_nombre }}</div>
                    </div>
                    <div v-if="activo.imagen_url" class="w-40 border-l border-gray-300 flex items-center justify-center p-1 bg-white">
                        <img :src="activo.imagen_url" class="max-w-full max-h-32 object-contain" alt="Foto">
                    </div>
                </div>
            </div>

            <!-- Description -->
            <div class="border border-gray-300">
                <div class="bg-gray-100 p-2 border-b border-gray-300 font-bold uppercase text-center text-sm">
                    II. Especificaciones Técnicas
                </div>
                <div class="grid grid-cols-2 text-sm">
                    <div class="p-2 border-r border-b border-gray-300"><span class="font-bold">Marca:</span> {{ activo.marca || 'N/A' }}</div>
                    <div class="p-2 border-b border-gray-300"><span class="font-bold">Modelo:</span> {{ activo.modelo || 'N/A' }}</div>
                    
                    <div class="p-2 border-r border-b border-gray-300"><span class="font-bold">Color:</span> {{ activo.color || 'N/A' }}</div>
                    <div class="p-2 border-b border-gray-300"><span class="font-bold">Serie:</span> {{ activo.serie || 'N/A' }}</div>
                </div>
            </div>

            <!-- Location -->
            <div class="border border-gray-300">
                <div class="bg-gray-100 p-2 border-b border-gray-300 font-bold uppercase text-center text-sm">
                    III. Ubicación y Responsabilidad
                </div>
                <div class="grid grid-cols-1 text-sm">
                    <div class="p-2 border-b border-gray-300"><span class="font-bold">Departamento / Área:</span> {{ activo.departamento }}</div>
                    <div class="p-2 border-b border-gray-300"><span class="font-bold">Ubicación Física:</span> {{ activo.ubicacion }}</div>
                    <div class="p-2 border-b border-gray-300"><span class="font-bold">Responsable Asignado:</span> {{ activo.responsable || 'Sin Asignar' }}</div>
                </div>
            </div>

            <!-- Financials -->
            <div class="border border-gray-300">
                <div class="bg-gray-100 p-2 border-b border-gray-300 font-bold uppercase text-center text-sm">
                    IV. Información Financiera
                </div>
                <div class="grid grid-cols-3 text-sm">
                    <div class="p-2 border-r border-gray-300"><span class="font-bold">Fecha Adquisición:</span><br>{{ activo.fecha_adquisicion }}</div>
                    <div class="p-2 border-r border-gray-300"><span class="font-bold">Valor Original:</span><br>{{ formatCurrency(activo.valor_adquisicion) }}</div>
                    <div class="p-2"><span class="font-bold">Valor Actual (Neto):</span><br>{{ formatCurrency(activo.valor_neto) }}</div>
                </div>
            </div>

            <!-- Signatures -->
             <div class="mt-16 pt-8">
                <div class="flex justify-between text-center">
                    <div class="w-1/3">
                        <div class="border-t border-black mb-2 mx-8"></div>
                        <p class="font-bold text-sm">Elaborado Por</p>
                        <p class="text-xs text-gray-500">Unidad de Activos Fijos</p>
                    </div>
                    <div class="w-1/3">
                        <div class="border-t border-black mb-2 mx-8"></div>
                        <p class="font-bold text-sm">Revisado Por</p>
                        <p class="text-xs text-gray-500">Dirección Administrativa</p>
                    </div>
                    <div class="w-1/3">
                        <div class="border-t border-black mb-2 mx-8"></div>
                        <p class="font-bold text-sm">Recibido Conforme</p>
                        <p class="text-xs text-gray-500">Responsable del Bien</p>
                    </div>
                </div>
            </div>

        </div>
        
        <!-- Footer -->
        <div class="text-center text-xs text-gray-400 mt-12 border-t pt-4">
            <p>Este documento es propiedad de la Alcaldía Municipal de Nindirí. Cualquier alteración invalida su contenido.</p>
            <p>Generado por Sistema SIAFNIN el {{ new Date().toLocaleString() }}</p>
        </div>
    </div>
</template>

<style>
@media print {
    @page { margin: 0; size: letter portrait; }
    body { background: white; margin: 0.5cm; }
    /* Hide unwanted elements if they leak in */
    nav, header, footer, .no-print { display: none !important; }
}
</style>

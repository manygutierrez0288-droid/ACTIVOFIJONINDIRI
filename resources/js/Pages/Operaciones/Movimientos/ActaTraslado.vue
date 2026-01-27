<script setup>
import { Head, usePage } from '@inertiajs/vue3';
import { onMounted, computed } from 'vue';

const props = defineProps({
    movimiento: Object,
    fecha_emision: String,
});

const page = usePage();

onMounted(() => {
    setTimeout(() => {
        window.print();
    }, 500);
});

const qrCodeUrl = computed(() => {
    const origin = page.props.app_url || (typeof window !== 'undefined' ? window.location.origin : '');
    return `https://api.qrserver.com/v1/create-qr-code/?size=80x80&data=${encodeURIComponent(origin + '/activos/' + props.movimiento.activo_fijo_id)}`;
});
</script>

<template>
    <Head title="Acta de Traslado de Activo Fijo" />

    <div class="bg-white min-h-screen p-12 max-w-4xl mx-auto text-gray-900 leading-relaxed print:p-0">
        <!-- Header -->
        <div class="flex items-center justify-between border-b-4 border-double border-gray-800 pb-6 mb-8">
            <div class="w-32">
                <img src="/images/logo_nindiri.png" alt="Logo Alcaldía" class="w-full h-auto" />
            </div>
            <div class="text-center flex-1">
                <h1 class="text-2xl font-black uppercase tracking-widest leading-tight">Alcaldía Municipal de Nindirí</h1>
                <p class="text-lg font-bold text-gray-700 tracking-wide">Dirección de Administración y Finanzas</p>
                <p class="text-md font-semibold text-gray-600 italic">Unidad de Control de Activos Fijos</p>
                <div class="mt-4 inline-block px-6 py-1 bg-indigo-900 text-white font-bold uppercase text-sm rounded-full">
                    Acta de Traslado Interno
                </div>
            </div>
            <div class="w-32 text-right">
                <p class="text-[10px] font-bold uppercase text-gray-400">Código Acta</p>
                <p class="font-mono text-sm font-bold">TRAS-{{ new Date().getFullYear() }}-{{ movimiento.id.toString().padStart(4, '0') }}</p>
                <p class="text-[10px] font-bold uppercase text-gray-400 mt-2">Fecha</p>
                <p class="text-sm font-bold">{{ fecha_emision }}</p>
            </div>
        </div>

        <!-- Body -->
        <div class="space-y-8 text-justify">
            <p>
                Se extiende la presente para formalizar el <strong>traslado interno</strong> de los bienes muebles descritos a continuación, autorizando el cambio de ubicación y de responsable dentro de la estructura organizativa de la Alcaldía de Nindirí.
            </p>

            <!-- Origin and Destination Info -->
            <div class="grid grid-cols-2 gap-8">
                <div class="bg-gray-50 p-4 border border-gray-200 rounded-lg">
                    <h3 class="text-xs font-black uppercase text-red-600 mb-3 tracking-widest">I. Origen</h3>
                    <div class="space-y-1 text-sm">
                        <p><span class="font-bold">Ubicación:</span> {{ movimiento.ubicacion_origen?.nombre }}</p>
                        <p><span class="font-bold">Responsable:</span> {{ movimiento.responsable_origen?.nombre }}</p>
                        <p class="text-xs text-gray-500 italic">{{ movimiento.responsable_origen?.cargo?.nombre }}</p>
                    </div>
                </div>
                <div class="bg-blue-50 p-4 border border-blue-100 rounded-lg">
                    <h3 class="text-xs font-black uppercase text-blue-600 mb-3 tracking-widest">II. Destino</h3>
                    <div class="space-y-1 text-sm">
                        <p><span class="font-bold">Ubicación:</span> {{ movimiento.ubicacion_destino?.nombre }}</p>
                        <p><span class="font-bold">Responsable:</span> {{ movimiento.responsable_destino?.nombre }}</p>
                        <p class="text-xs text-gray-500 italic">{{ movimiento.responsable_destino?.cargo?.nombre }}</p>
                    </div>
                </div>
            </div>

            <!-- Asset Info -->
            <div class="border border-gray-200 rounded-lg overflow-hidden">
                <div class="bg-indigo-900 text-white py-2 px-4 text-xs font-black uppercase tracking-widest flex justify-between">
                    <span>III. Datos del Activo</span>
                    <span class="font-mono text-[10px]">{{ movimiento.activo_fijo?.codigo_inventario }}</span>
                </div>
                <div class="p-4 text-sm grid grid-cols-2 gap-4">
                    <div class="col-span-2"><span class="font-bold">Nombre del Bien:</span> {{ movimiento.activo_fijo?.nombre }}</div>
                    <div><span class="font-bold">Categoría:</span> {{ movimiento.activo_fijo?.categoria?.nombre }}</div>
                    <div><span class="font-bold">Serie:</span> {{ movimiento.activo_fijo?.numero_serie || 'N/A' }}</div>
                </div>
                <div class="px-4 pb-4 text-sm">
                    <div class="p-2 bg-gray-100 rounded border border-gray-200">
                        <span class="font-bold text-xs uppercase text-gray-500 block mb-1">Motivo del Traslado:</span>
                        {{ movimiento.motivo }}
                    </div>
                </div>
            </div>

            <!-- Clause -->
            <p class="text-sm italic border-l-4 border-indigo-200 pl-4">
                El nuevo responsable asume formalmente el cuido y custodia del bien a partir de la fecha de suscripción de la presente acta, exonerando al responsable de origen de cualquier cargo futuro sobre el mismo.
            </p>

            <!-- Signatures Section -->
            <div class="mt-20 pt-12">
                <div class="grid grid-cols-2 gap-16 text-center">
                    <div>
                        <div class="border-t-2 border-gray-800 mb-2 mx-4"></div>
                        <p class="font-bold text-xs uppercase">Entrega (Origen)</p>
                        <p class="text-[10px] uppercase text-gray-500">{{ movimiento.responsable_origen?.nombre }}</p>
                    </div>
                    <div>
                        <div class="border-t-2 border-gray-800 mb-2 mx-4"></div>
                        <p class="font-bold text-xs uppercase">Recibe (Destino)</p>
                        <p class="text-[10px] uppercase text-gray-500">{{ movimiento.responsable_destino?.nombre }}</p>
                    </div>
                    <div class="col-span-2 pt-8 w-1/2 mx-auto">
                        <div class="border-t-2 border-gray-800 mb-2 mx-8"></div>
                        <p class="font-bold text-xs uppercase">Autorizado Por</p>
                        <p class="text-[10px] text-gray-500">Unidad de Activos Fijos</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="mt-16 text-[10px] text-gray-400 border-t pt-4 flex justify-between items-end">
            <div>
                <p>Alcaldía de Nindirí - Nindirí es mi Linda Tierra</p>
                <p>Generado por SIAFNIN - Nicaragua</p>
            </div>
            <div class="flex flex-col items-center">
                <img :src="qrCodeUrl" alt="QR Code" class="w-16 h-16 opacity-50 mb-1" />
                <p class="font-mono">VALIDACIÓN DIGITAL</p>
            </div>
        </div>
    </div>
</template>

<style>
@media print {
    @page { margin: 1cm; size: letter; }
    body { background: white; }
    nav, header, footer, .no-print { display: none !important; }
}
</style>

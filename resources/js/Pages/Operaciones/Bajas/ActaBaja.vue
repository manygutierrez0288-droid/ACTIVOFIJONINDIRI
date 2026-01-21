<script setup>
import { Head } from '@inertiajs/vue3';
import { onMounted } from 'vue';

const props = defineProps({
    baja: Object,
    fecha_emision: String,
});

onMounted(() => {
    setTimeout(() => {
        window.print();
    }, 500);
});
</script>

<template>
    <Head title="Acta de Baja de Activo Fijo" />

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
                <div class="mt-4 inline-block px-6 py-1 bg-red-700 text-white font-bold uppercase text-sm rounded-full">
                    Acta de Baja Definitiva
                </div>
            </div>
            <div class="w-32 text-right">
                <p class="text-[10px] font-bold uppercase text-gray-400">Código Acta</p>
                <p class="font-mono text-sm font-bold">BAJA-{{ new Date().getFullYear() }}-{{ baja.id.toString().padStart(4, '0') }}</p>
                <p class="text-[10px] font-bold uppercase text-gray-400 mt-2">Fecha</p>
                <p class="text-sm font-bold">{{ fecha_emision }}</p>
            </div>
        </div>

        <!-- Body -->
        <div class="space-y-8 text-justify text-sm">
            <p>
                En la ciudad de Nindirí, departamento de Masaya, se suscribe la presente acta para formalizar la <strong>desincorporación y baja definitiva</strong> del Inventario General de Activos Fijos del bien mueble que se detalla a continuación.
            </p>

            <!-- Asset Info -->
            <div class="border border-red-200 rounded-lg overflow-hidden ring-1 ring-red-100">
                <div class="bg-red-50 text-red-800 py-2 px-4 text-xs font-black uppercase tracking-widest flex justify-between border-b border-red-100">
                    <span>Identificación del Bien</span>
                    <span class="font-mono">{{ baja.activo_fijo?.codigo_inventario }}</span>
                </div>
                <div class="p-4 grid grid-cols-2 gap-y-3">
                    <div class="col-span-2"><span class="font-bold text-gray-600">Descripción:</span> <span class="uppercase font-semibold">{{ baja.activo_fijo?.nombre }}</span></div>
                    <div><span class="font-bold text-gray-600">Categoría:</span> {{ baja.activo_fijo?.categoria?.nombre }}</div>
                    <div><span class="font-bold text-gray-600">Ubicación Final:</span> {{ baja.activo_fijo?.ubicacion?.nombre }}</div>
                    <div><span class="font-bold text-gray-600">Serie:</span> {{ baja.activo_fijo?.numero_serie || 'N/A' }}</div>
                    <div><span class="font-bold text-gray-600">Responsable:</span> {{ baja.activo_fijo?.responsable?.nombre }}</div>
                </div>
            </div>

            <!-- Justification -->
            <div class="space-y-3">
                <h3 class="text-xs font-black uppercase text-gray-500 tracking-widest border-b pb-1">Justificación Legal y Técnica</h3>
                <div class="p-4 bg-gray-50 border border-gray-100 rounded text-gray-700 leading-relaxed italic">
                    <p class="mb-2"><span class="font-bold uppercase not-italic text-xs">Motivo:</span> {{ baja.motivo }}</p>
                    <p v-if="baja.documento_respaldo"><span class="font-bold uppercase not-italic text-xs">Ref. Documental:</span> {{ baja.documento_respaldo }}</p>
                </div>
            </div>

            <p>
                La baja se fundamenta en los dictámenes técnicos que determinan que el bien antes descrito ya no es útil para el servicio, se encuentra en estado de obsolescencia, daño irreparable o extravío debidamente justificado, autorizándose su retiro de los registros contables y físicos de la institución.
            </p>

            <!-- Signatures Section -->
            <div class="mt-20 pt-12">
                <div class="grid grid-cols-3 gap-8 text-center">
                    <div>
                        <div class="border-t-2 border-gray-800 mb-2 mx-2"></div>
                        <p class="font-bold text-[10px] uppercase">Responsable de Bienes</p>
                        <p class="text-[9px] text-gray-500">Unidad de Activos Fijos</p>
                    </div>
                    <div>
                        <div class="border-t-2 border-gray-800 mb-2 mx-2"></div>
                        <p class="font-bold text-[10px] uppercase">Contabilidad</p>
                        <p class="text-[9px] text-gray-500">Dirección Financiera</p>
                    </div>
                    <div>
                        <div class="border-t-2 border-gray-800 mb-2 mx-2"></div>
                        <p class="font-bold text-[10px] uppercase">Autorizado Por</p>
                        <p class="text-[9px] text-gray-500">Alcalde / Vice-Alcalde</p>
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
            <div class="text-right italic">
                <p>"Honor y Transparencia en la Gestión Municipal"</p>
            </div>
        </div>
    </div>
</template>

<style>
@media print {
    @page { margin: 1.5cm; size: letter; }
    body { background: white; }
    nav, header, footer, .no-print { display: none !important; }
}
</style>

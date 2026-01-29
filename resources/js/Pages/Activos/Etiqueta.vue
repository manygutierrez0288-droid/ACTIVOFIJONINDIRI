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

const qrCodeUrl = computed(() => {
    const origin = page.props.app_url || (typeof window !== 'undefined' ? window.location.origin : '');
    const id = props.activo?.id ?? '';
    return `https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=${encodeURIComponent(origin + '/consultar-activo/' + id)}`;
});
</script>

<template>
    <Head :title="`Etiqueta - ${activo.id}`" />

    <div class="label-container bg-white">
        <!-- Main Label Area (optimized for small sticker) -->
        <div class="sticker border-2 border-black flex flex-col items-center justify-center">
            <div class="header w-full border-b border-black text-center py-0.5 mb-1">
                <h1 class="font-black text-[10px] uppercase tracking-tighter">ALCALDÍA NINDIRÍ</h1>
                <p class="text-[7px] font-bold text-gray-600 uppercase">Activos Fijos</p>
            </div>
            
            <img :src="qrCodeUrl" alt="QR" class="w-24 h-24 mb-1" />
            
            <div class="info w-full text-center px-1">
                <p class="font-mono font-black text-[11px] leading-tight break-all border-y border-gray-200 py-0.5">
                    {{ activo.codigo_inventario }}
                </p>
                <div class="mt-1">
                   <p class="text-[8px] font-bold uppercase truncate max-w-full leading-none">{{ activo.nombre }}</p>
                   <p class="text-[6px] text-gray-500 font-bold uppercase mt-0.5">{{ activo.categoria }}</p>
                </div>
            </div>

            <div class="footer w-full border-t border-gray-100 mt-1 py-0.5 text-[5px] text-center font-bold text-gray-400">
                SIAFNIN - SISTEMA DE CONTROL
            </div>
        </div>
        
        <div class="no-print mt-10 text-center p-4 bg-blue-50 text-blue-700 rounded-lg max-w-sm mx-auto">
            <p class="font-bold text-sm mb-2">💡 Sugerencias de Impresión:</p>
            <ul class="text-xs space-y-1 text-left">
                <li>• Usa una escala del 100% en los ajustes de impresión.</li>
                <li>• Papel recomendado: Adhesivo / Sticker.</li>
                <li>• Tamaño sugerido: 5cm x 7cm (2" x 3").</li>
            </ul>
        </div>
    </div>
</template>

<style>
/* Reset for print */
@media print {
    @page { margin: 0; size: auto; }
    body { background: white; margin: 0; padding: 0; }
    .no-print { display: none !important; }
}

.label-container {
    width: 100%;
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 1rem;
    font-family: 'Inter', system-ui, -apple-system, sans-serif;
}

.sticker {
    width: 5.5cm;
    min-height: 7.5cm;
    padding: 0.2cm;
    border-radius: 4px;
    box-shadow: 0 0 10px rgba(0,0,0,0.05);
}

@media print {
    .label-container {
        min-height: auto;
        padding: 0;
        display: block;
    }
    .sticker {
        width: 100%;
        height: 100vh;
        border: none;
        box-shadow: none;
        padding: 0.5cm;
    }
}
</style>

<script setup>
import { Head, usePage } from '@inertiajs/vue3';
import { onMounted, computed } from 'vue';

const props = defineProps({
    vehiculo: Object,
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
    const id = props.vehiculo?.id ?? '';
    return `https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=${encodeURIComponent(origin + '/vehiculos/' + id)}`;
});
</script>

<template>
    <Head :title="`Ficha Técnica Vehículo - ${vehiculo.placa}`" />

    <div class="bg-white min-h-screen p-8 max-w-4xl mx-auto printable">
        <!-- Header -->
        <div class="flex items-center justify-between border-b-2 border-gray-800 pb-4 mb-8">
            <div class="w-32">
                <img src="/images/logo_nindiri.png" alt="Logo Alcaldía" class="w-full h-auto" />
            </div>
            <div class="text-center flex-1">
                <h1 class="text-2xl font-bold uppercase tracking-wide">Alcaldía Municipal de Nindirí</h1>
                <p class="text-lg font-medium text-gray-700">Departamento de Activos Fijos</p>
                <h2 class="text-xl font-bold mt-2 uppercase underline decoration-2 underline-offset-4">Ficha Técnica de Vehículo</h2>
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
                    I. Datos de Identificación del Vehículo
                </div>
                <div class="flex border-b border-gray-300">
                    <div class="flex-1 grid grid-cols-2 text-sm">
                        <div class="p-2 border-r border-b border-gray-300"><span class="font-bold">Placa / Matrícula:</span> <span class="bg-yellow-400 px-2 font-mono font-bold border border-black">{{ vehiculo.placa }}</span></div>
                        <div class="p-2 border-b border-gray-300"><span class="font-bold">Código Inventario:</span> <span class="text-indigo-700 font-mono font-bold">{{ vehiculo.codigo_inventario || 'N/A' }}</span></div>
                        
                        <div class="p-2 border-r border-b border-gray-300"><span class="font-bold">Descripción:</span> {{ vehiculo.nombre }}</div>
                        <div class="p-2 border-b border-gray-300"><span class="font-bold">Año Fabricación:</span> {{ vehiculo.anio }}</div>

                        <div class="p-2 border-r border-b border-gray-300"><span class="font-bold">Número de Circulación:</span> {{ vehiculo.numero_circulacion || 'N/A' }}</div>
                        <div class="p-2 border-b border-gray-300"><span class="font-bold">Categoría:</span> {{ vehiculo.categoria }}</div>
                    </div>
                    <div v-if="vehiculo.imagen_url" class="w-48 border-l border-gray-300 flex items-center justify-center p-1 bg-white">
                        <img :src="vehiculo.imagen_url" class="max-w-full max-h-40 object-contain" alt="Foto">
                    </div>
                </div>
            </div>

            <!-- Technical Specifications -->
            <div class="border border-gray-300">
                <div class="bg-indigo-50 p-2 border-b border-gray-300 font-bold uppercase text-center text-sm">
                    II. Especificaciones Técnicas
                </div>
                <div class="grid grid-cols-2 text-sm">
                    <div class="p-2 border-r border-b border-gray-300"><span class="font-bold">Marca:</span> {{ vehiculo.marca || 'N/A' }}</div>
                    <div class="p-2 border-b border-gray-300"><span class="font-bold">Modelo:</span> {{ vehiculo.modelo || 'N/A' }}</div>
                    
                    <div class="p-2 border-r border-b border-gray-300"><span class="font-bold">Color:</span> {{ vehiculo.color || 'N/A' }}</div>
                    <div class="p-2 border-b border-gray-300"><span class="font-bold">VIN / Chasis:</span> {{ vehiculo.numero_serie || 'N/A' }}</div>

                    <div class="p-2 border-r border-b border-gray-300"><span class="font-bold">Tipo Combustible:</span> {{ vehiculo.tipo_combustible || 'N/A' }}</div>
                    <div class="p-2 border-b border-gray-300"><span class="font-bold">Kilometraje Actual:</span> {{ vehiculo.kilometraje }} km</div>

                    <div class="p-2 border-r border-gray-300"><span class="font-bold">Capacidad Pasajeros:</span> {{ vehiculo.capacidad_pasajeros || 'N/A' }}</div>
                    <div class="p-2"><span class="font-bold">Estado Físico:</span> {{ vehiculo.estado }}</div>
                </div>
            </div>

            <!-- Location -->
            <div class="border border-gray-300">
                <div class="bg-gray-100 p-2 border-b border-gray-300 font-bold uppercase text-center text-sm">
                    III. Asignación y Ubicación
                </div>
                <div class="grid grid-cols-1 text-sm">
                    <div class="p-2 border-b border-gray-300"><span class="font-bold">Departamento Responsable:</span> {{ vehiculo.departamento }}</div>
                    <div class="p-2 border-b border-gray-300"><span class="font-bold">Ubicación Física:</span> {{ vehiculo.ubicacion }}</div>
                    <div class="p-2 border-b border-gray-300"><span class="font-bold">Conductor / Responsable:</span> {{ vehiculo.responsable || 'Sin Asignar' }}</div>
                </div>
            </div>

            <!-- Financials -->
            <div class="border border-gray-300">
                <div class="bg-gray-100 p-2 border-b border-gray-300 font-bold uppercase text-center text-sm">
                    IV. Información Financiera
                </div>
                <div class="grid grid-cols-3 text-sm">
                    <div class="p-2 border-r border-gray-300"><span class="font-bold">Fecha Adquisición:</span><br>{{ vehiculo.fecha_adquisicion }}</div>
                    <div class="p-2 border-r border-gray-300"><span class="font-bold">Valor Original:</span><br>{{ formatCurrency(vehiculo.valor_adquisicion) }}</div>
                    <div class="p-2"><span class="font-bold">Valor Neto Actual:</span><br>{{ formatCurrency(vehiculo.valor_neto) }}</div>
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
                        <p class="text-xs text-gray-500">Responsable / Conductor</p>
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
    body { background: white; margin: 0; }
    .printable { padding: 1cm; width: 100%; max-width: none; }
    nav, header, footer, .no-print { display: none !important; }
}
</style>

<script setup>
import { Head } from '@inertiajs/vue3';
import { onMounted } from 'vue';

const props = defineProps({
    activo: Object,
    fecha_emision: String,
});

onMounted(() => {
    setTimeout(() => {
        window.print();
    }, 500);
});
</script>

<template>
    <Head title="Acta de Asignación de Activo Fijo" />

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
                <div class="mt-4 inline-block px-6 py-1 bg-gray-900 text-white font-bold uppercase text-sm rounded-full">
                    Acta de Asignación de Bienes
                </div>
            </div>
            <div class="w-32 text-right">
                <p class="text-[10px] font-bold uppercase text-gray-400">Código Acta</p>
                <p class="font-mono text-sm font-bold">ASIG-{{ new Date().getFullYear() }}-{{ activo.id.toString().padStart(4, '0') }}</p>
                <p class="text-[10px] font-bold uppercase text-gray-400 mt-2">Fecha</p>
                <p class="text-sm font-bold">{{ fecha_emision }}</p>
            </div>
        </div>

        <!-- Body -->
        <div class="space-y-8 text-justify">
            <p>
                Por medio de la presente, se hace constar la entrega y asignación del activo fijo que se detalla a continuación, el cual queda bajo la custodia y responsabilidad del servidor público mencionado, para el desempeño exclusivo de sus funciones laborales en esta municipalidad.
            </p>

            <!-- Responsable Info -->
            <div class="bg-gray-50 p-4 border border-gray-200 rounded-lg">
                <h3 class="text-xs font-black uppercase text-gray-500 mb-3 tracking-widest">I. Datos del Responsable</h3>
                <div class="grid grid-cols-2 gap-y-2 text-sm">
                    <div><span class="font-bold">Nombre Completo:</span> {{ activo.responsable?.nombre || 'N/A' }}</div>
                    <div><span class="font-bold">Cargo:</span> {{ activo.responsable?.cargo?.nombre || 'N/A' }}</div>
                    <div><span class="font-bold">Departamento/Área:</span> {{ activo.departamento?.nombre || 'N/A' }}</div>
                    <div><span class="font-bold">Ubicación Asignada:</span> {{ activo.ubicacion?.nombre || 'N/A' }}</div>
                </div>
            </div>

            <!-- Asset Info -->
            <div class="border border-gray-200 rounded-lg overflow-hidden">
                <div class="bg-gray-900 text-white py-2 px-4 text-xs font-black uppercase tracking-widest">
                    II. Especificaciones del Bien
                </div>
                <table class="w-full text-sm">
                    <tbody class="divide-y divide-gray-100">
                        <tr>
                            <td class="px-4 py-2 bg-gray-50 font-bold w-1/3">Código de Inventario</td>
                            <td class="px-4 py-2 font-mono text-indigo-700 font-bold">{{ activo.codigo_inventario || 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-2 bg-gray-50 font-bold">Descripción del Bien</td>
                            <td class="px-4 py-2 uppercase font-semibold">{{ activo.nombre }}</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-2 bg-gray-50 font-bold">Categoría</td>
                            <td class="px-4 py-2">{{ activo.categoria?.nombre || 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-2 bg-gray-50 font-bold">Marca / Modelo</td>
                            <td class="px-4 py-2">{{ activo.marca?.nombre || 'N/A' }} / {{ activo.modelo?.nombre || 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-2 bg-gray-50 font-bold">Número de Serie</td>
                            <td class="px-4 py-2 font-mono">{{ activo.numero_serie || 'N/A' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Commitment Clause -->
            <div class="text-sm space-y-4 border-l-4 border-gray-300 pl-4 italic">
                <p>
                    <strong>Compromiso:</strong> El responsable asume la obligación de velar por el buen uso, cuido y conservación del bien asignado. En caso de pérdida, deterioro por negligencia o mal uso, se procederá conforme a lo estipulado en las Normas Técnicas de Control Interno de la Contraloría General de la República y la Ley de Servicio Civil y Carrera Administrativa.
                </p>
                <p>
                    Cualquier traslado o movimiento de este bien debe ser debidamente notificado y autorizado por la Unidad de Activos Fijos.
                </p>
            </div>

            <!-- Signatures Section -->
            <div class="mt-20 pt-12">
                <div class="grid grid-cols-3 gap-8 text-center">
                    <div>
                        <div class="border-t-2 border-gray-800 mb-2 mx-4"></div>
                        <p class="font-bold text-xs uppercase">Entregado Por</p>
                        <p class="text-[10px] text-gray-500">Unidad de Activos Fijos</p>
                    </div>
                    <div>
                        <div class="border-t-2 border-gray-800 mb-2 mx-4"></div>
                        <p class="font-bold text-xs uppercase">Vto. Bueno</p>
                        <p class="text-[10px] text-gray-500">Director(a) Administrativo</p>
                    </div>
                    <div>
                        <div class="border-t-2 border-gray-800 mb-2 mx-4"></div>
                        <p class="font-bold text-xs uppercase">Recibido Conforme</p>
                        <p class="text-[10px] text-gray-500 uppercase">{{ activo.responsable?.nombre || 'Servidor Público' }}</p>
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
                <img :src="`https://api.qrserver.com/v1/create-qr-code/?size=80x80&data=${encodeURIComponent(route('activos.show', activo.id))}`" alt="QR Code" class="w-16 h-16 opacity-50 mb-1" />
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

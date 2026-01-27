<script setup>
import { Head } from '@inertiajs/vue3';
import { 
    ShieldCheck, MapPin, User, Tag, 
    Calendar, Package, Info, CheckCircle2 
} from 'lucide-vue-next';

const props = defineProps({
    activo: Object,
});

const formatCurrency = (value) => {
    return new Intl.NumberFormat('es-NI', {
        style: 'currency',
        currency: 'NIO',
    }).format(value);
};
</script>

<template>
    <Head :title="`Consulta de Activo - ${activo.codigo_inventario}`" />

    <div class="min-h-screen bg-slate-50 flex flex-col items-center p-4 sm:p-8 font-sans">
        <!-- Brand Header -->
        <div class="w-full max-w-lg flex flex-col items-center mb-8">
            <img src="/images/logo_alcaldia_nindiri.png" alt="Logo" class="h-20 w-auto mb-4 drop-shadow-sm" />
            <h1 class="text-xl font-black text-indigo-900 uppercase tracking-tighter text-center">
                Alcaldía de Nindirí
            </h1>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.3em]">Validación de Activo Fijo</p>
        </div>

        <!-- Main Card -->
        <div class="w-full max-w-lg bg-white rounded-[2.5rem] shadow-2xl shadow-indigo-100 overflow-hidden border border-white">
            <!-- Status Badge Header -->
            <div class="bg-indigo-600 p-6 flex flex-col items-center text-white relative overflow-hidden">
                <div class="absolute -right-8 -top-8 bg-white/10 w-32 h-32 rounded-full"></div>
                
                <div class="bg-white/20 backdrop-blur-md px-4 py-1.5 rounded-full flex items-center gap-2 mb-4 border border-white/20">
                    <ShieldCheck class="w-4 h-4 text-emerald-400" />
                    <span class="text-xs font-black uppercase tracking-widest">Activo Verificado</span>
                </div>

                <div class="text-center z-10">
                    <p class="text-[10px] font-bold text-indigo-200 uppercase tracking-widest mb-1">Código de Inventario</p>
                    <h2 class="text-3xl font-black font-mono tracking-tighter">{{ activo.codigo_inventario }}</h2>
                </div>
            </div>

            <!-- Content -->
            <div class="p-8 space-y-8">
                <!-- Image -->
                <div v-if="activo.imagen_url" class="relative group">
                    <img :src="activo.imagen_url" class="w-full h-56 object-cover rounded-3xl shadow-lg border-4 border-slate-50" />
                    <div class="absolute inset-0 rounded-3xl bg-gradient-to-t from-black/20 to-transparent"></div>
                </div>

                <!-- Basic Info -->
                <div class="space-y-4">
                    <h3 class="text-2xl font-black text-slate-900 border-b-2 border-slate-100 pb-2 leading-tight uppercase">
                        {{ activo.nombre }}
                    </h3>
                    
                    <div class="grid grid-cols-1 gap-6">
                        <!-- Ubicación -->
                        <div class="flex items-start gap-4">
                            <div class="p-3 bg-slate-50 rounded-2xl">
                                <MapPin class="w-5 h-5 text-indigo-500" />
                            </div>
                            <div>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-0.5">Ubicación Actual</p>
                                <p class="text-sm font-black text-slate-800 uppercase">{{ activo.ubicacion }}</p>
                                <p class="text-xs text-slate-500 font-medium">{{ activo.departamento }}</p>
                            </div>
                        </div>

                        <!-- Responsable -->
                        <div class="flex items-start gap-4">
                            <div class="p-3 bg-slate-50 rounded-2xl">
                                <User class="w-5 h-5 text-indigo-500" />
                            </div>
                            <div>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-0.5">Responsable Asignado</p>
                                <p class="text-sm font-black text-slate-800 uppercase">{{ activo.responsable || 'Sin Asignación' }}</p>
                            </div>
                        </div>

                        <!-- Estado -->
                        <div class="flex items-center gap-4 bg-emerald-50 p-4 rounded-3xl border border-emerald-100">
                            <div class="p-2 bg-white rounded-xl shadow-sm">
                                <CheckCircle2 class="w-5 h-5 text-emerald-500" />
                            </div>
                            <div>
                                <p class="text-[10px] font-black text-emerald-600/60 uppercase tracking-widest leading-none mb-1">Estado del Bien</p>
                                <p class="text-sm font-black text-emerald-700 uppercase leading-none">{{ activo.estado_nombre }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer Cert -->
                <div class="pt-6 border-t border-slate-100 text-center">
                    <p class="text-[9px] text-slate-300 font-bold uppercase tracking-widest">
                        Documento Digital generado por SIAFNIN<br/>
                        Sincronizado con base de datos institucional
                    </p>
                </div>
            </div>
        </div>

        <!-- Back to Home / Contact -->
        <p class="mt-12 text-xs text-slate-400 font-medium">
            © {{ new Date().getFullYear() }} Alcaldía de Nindirí
        </p>
    </div>
</template>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;700;900&display=swap');

:deep(body) {
    font-family: 'Inter', sans-serif;
}
</style>

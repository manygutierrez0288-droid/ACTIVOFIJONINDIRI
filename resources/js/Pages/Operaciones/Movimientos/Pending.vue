<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import Modal from '@/Components/Modal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import InputLabel from '@/Components/InputLabel.vue';
import { 
    Clock, 
    CheckCircle, 
    XCircle, 
    ArrowRight, 
    MapPin, 
    User,
    Info,
    Calendar,
    ChevronRight,
    AlertCircle
} from 'lucide-vue-next';

const props = defineProps({
    movimientos: Object
});

const processing = ref(null);
const showRejectionModal = ref(false);
const rejectionForm = ref({
    id: null,
    motivo_rechazo: ''
});

const formatDate = (dateString) => {
    return new Date(dateString).toLocaleDateString('es-NI', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric'
    });
};

const handleAutorizar = (id) => {
    if (confirm('¿Está seguro de autorizar este traslado? El activo cambiará de ubicación inmediatamente.')) {
        processing.value = id;
        router.post(route('movimientos.autorizar', id), {}, {
            onFinish: () => processing.value = null,
            preserveScroll: true
        });
    }
};

const openRejectionModal = (id) => {
    rejectionForm.value.id = id;
    rejectionForm.value.motivo_rechazo = '';
    showRejectionModal.value = true;
};

const closeRejectionModal = () => {
    showRejectionModal.value = false;
    rejectionForm.value.id = null;
    rejectionForm.value.motivo_rechazo = '';
};

const submitRechazo = () => {
    if (!rejectionForm.value.motivo_rechazo.trim()) {
        alert('Por favor, indique el motivo del rechazo.');
        return;
    }

    processing.value = rejectionForm.value.id;
    router.post(route('movimientos.rechazar', rejectionForm.value.id), { 
        motivo_rechazo: rejectionForm.value.motivo_rechazo 
    }, {
        onSuccess: () => closeRejectionModal(),
        onFinish: () => processing.value = null,
        preserveScroll: true
    });
};
</script>

<template>
    <Head title="Traslados Pendientes" />
    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="font-black text-2xl text-gray-900 dark:text-white leading-tight">Autorización de Traslados</h2>
                    <p class="text-sm text-gray-500 mt-1">Revision y aprobación de movimientos de activos fijos.</p>
                </div>
                <div class="flex items-center gap-2 px-4 py-2 bg-amber-50 dark:bg-amber-900/20 rounded-full border border-amber-100 dark:border-amber-800/30">
                    <Clock class="w-4 h-4 text-amber-600" />
                    <span class="text-xs font-bold text-amber-700 dark:text-amber-400">{{ movimientos.total }} Pendientes</span>
                </div>
            </div>
        </template>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div v-if="movimientos.data.length === 0" class="bg-white dark:bg-gray-800 p-16 rounded-[2.5rem] shadow-sm border border-gray-100 dark:border-gray-700 text-center flex flex-col items-center">
                <div class="w-20 h-20 bg-emerald-50 dark:bg-emerald-900/20 rounded-full flex items-center justify-center mb-6">
                    <CheckCircle class="w-10 h-10 text-emerald-500" />
                </div>
                <h3 class="text-xl font-black text-gray-900 dark:text-white mb-2">Todo al día</h3>
                <p class="text-gray-500 dark:text-gray-400 max-w-sm">No hay solicitudes de traslado pendientes de autorización en este momento.</p>
            </div>

            <div v-else class="grid gap-6">
                <div v-for="mov in movimientos.data" :key="mov.id" 
                    class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden hover:shadow-md transition-all duration-300">
                    
                    <div class="flex flex-col lg:flex-row lg:items-center">
                        <!-- Asset Info Sidebar -->
                        <div class="lg:w-1/4 p-6 bg-gray-50/50 dark:bg-gray-900/20 border-b lg:border-b-0 lg:border-r border-gray-100 dark:border-gray-700">
                            <div class="flex flex-col">
                                <span class="text-[10px] font-black text-indigo-600 dark:text-indigo-400 uppercase tracking-widest mb-1">Activo Fijo</span>
                                <h4 class="font-black text-gray-900 dark:text-white line-clamp-2 mb-2">{{ mov.activo_fijo?.nombre }}</h4>
                                <span class="px-3 py-1 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 text-[10px] font-bold text-gray-400 w-fit">
                                    {{ mov.activo_fijo?.codigo_inventario }}
                                </span>
                            </div>
                        </div>

                        <!-- Movement Details -->
                        <div class="lg:flex-1 p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
                                <!-- Origin to Destination -->
                                <div class="flex items-center gap-6">
                                    <div class="space-y-4 flex-1">
                                        <div class="flex items-start gap-3">
                                            <div class="p-2 bg-gray-100 dark:bg-gray-700 rounded-xl">
                                                <MapPin class="w-4 h-4 text-gray-400" />
                                            </div>
                                            <div>
                                                <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest leading-none">Origen</p>
                                                <p class="text-xs font-bold text-gray-700 dark:text-gray-300 mt-1">{{ mov.ubicacion_origen?.nombre }}</p>
                                                <p class="text-[10px] text-gray-400">{{ mov.responsable_origen?.nombre }}</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="flex flex-col items-center">
                                        <div class="w-8 h-8 rounded-full bg-indigo-50 dark:bg-indigo-900/30 flex items-center justify-center border border-indigo-100 dark:border-indigo-800">
                                            <ArrowRight class="w-4 h-4 text-indigo-600" />
                                        </div>
                                    </div>

                                    <div class="space-y-4 flex-1">
                                        <div class="flex items-start gap-3">
                                            <div class="p-2 bg-indigo-100 dark:bg-indigo-900/30 rounded-xl">
                                                <MapPin class="w-4 h-4 text-indigo-600" />
                                            </div>
                                            <div>
                                                <p class="text-[9px] font-black text-indigo-600 dark:text-indigo-400 uppercase tracking-widest leading-none">Destino</p>
                                                <p class="text-xs font-black text-gray-900 dark:text-white mt-1">{{ mov.ubicacion_destino?.nombre }}</p>
                                                <p class="text-[10px] text-gray-400 font-bold text-indigo-500">{{ mov.responsable_destino?.nombre }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Metadata & Reason -->
                                <div class="space-y-4 bg-gray-50/50 dark:bg-gray-900/30 p-4 rounded-2xl border border-gray-100 dark:border-gray-800">
                                    <div class="flex items-center gap-4 text-[10px] font-bold text-gray-400 uppercase tracking-wider border-b border-gray-200 dark:border-gray-700 pb-2 mb-2">
                                        <div class="flex items-center gap-1.5">
                                            <Calendar class="w-3 h-3" />
                                            {{ formatDate(mov.fecha) }}
                                        </div>
                                        <div class="flex items-center gap-1.5 ml-auto">
                                            <User class="w-3 h-3" />
                                            Solictado por: {{ mov.user?.name }}
                                        </div>
                                    </div>
                                    <div class="flex items-start gap-2">
                                        <Info class="w-3.5 h-3.5 text-gray-300 flex-shrink-0 mt-0.5" />
                                        <p class="text-xs text-gray-500 line-clamp-2 italic">Motivo: {{ mov.motivo }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="p-6 bg-gray-50/80 dark:bg-gray-900/40 flex lg:flex-col justify-center gap-3">
                            <button @click="handleAutorizar(mov.id)" 
                                :disabled="processing === mov.id"
                                class="flex-1 lg:w-full px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-black rounded-xl shadow-lg shadow-emerald-600/20 transition-all flex items-center justify-center gap-2 group disabled:opacity-50 disabled:cursor-not-allowed">
                                <CheckCircle v-if="processing !== mov.id" class="w-4 h-4 group-hover:scale-110 transition-transform" />
                                <Clock v-else class="w-4 h-4 animate-spin" />
                                {{ processing === mov.id ? 'Procesando...' : 'Autorizar' }}
                            </button>
                            <button @click="openRejectionModal(mov.id)" 
                                :disabled="processing === mov.id"
                                class="flex-1 lg:w-full px-4 py-2.5 bg-white dark:bg-gray-800 hover:bg-rose-50 dark:hover:bg-rose-900/20 text-rose-600 text-xs font-black rounded-xl border border-gray-200 dark:border-gray-700 hover:border-rose-200 transition-all flex items-center justify-center gap-2 group disabled:opacity-50 disabled:cursor-not-allowed">
                                <XCircle v-if="processing !== mov.id" class="w-4 h-4 group-hover:scale-110 transition-transform" />
                                <Clock v-else class="w-4 h-4 animate-spin" />
                                Rechazar
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pagination -->
            <div v-if="movimientos.links && movimientos.links.length > 3" class="mt-8 flex justify-center">
                 <div class="flex items-center gap-2 p-2 bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700">
                    <template v-for="(link, key) in movimientos.links" :key="key">
                        <Link 
                            v-if="link.url" 
                            :href="link.url"
                            class="w-10 h-10 flex items-center justify-center rounded-xl text-xs transition-all duration-200"
                            :class="{
                                'bg-indigo-600 text-white font-black shadow-lg shadow-indigo-600/30 scale-110': link.active,
                                'text-gray-500 hover:bg-gray-50 dark:hover:bg-gray-700 font-bold': !link.active
                            }"
                            v-html="link.label.replace('Previous', '').replace('Next', '')"
                        />
                        <span v-else class="w-10 h-10 flex items-center justify-center text-gray-300 dark:text-gray-600 text-[10px] font-black" v-html="link.label.replace('Previous', '').replace('Next', '')"></span>
                    </template>
                 </div>
            </div>
        </div>

        <!-- Rejection Modal -->
        <Modal :show="showRejectionModal" @close="closeRejectionModal" maxWidth="lg">
            <div class="p-6">
                <div class="flex items-center gap-3 mb-6">
                    <div class="p-3 bg-rose-100 dark:bg-rose-900/30 rounded-2xl">
                        <XCircle class="w-6 h-6 text-rose-600" />
                    </div>
                    <div>
                        <h3 class="text-lg font-black text-gray-900 dark:text-white">Rechazar Traslado</h3>
                        <p class="text-sm text-gray-500">Por favor, indique el motivo por el cual no autoriza este movimiento.</p>
                    </div>
                </div>

                <div class="space-y-4">
                    <div>
                        <InputLabel for="motivo_rechazo" value="Motivo del Rechazo" class="mb-2" />
                        <textarea 
                            id="motivo_rechazo" 
                            v-model="rejectionForm.motivo_rechazo" 
                            rows="4" 
                            class="w-full rounded-2xl border-gray-200 dark:border-gray-700 dark:bg-gray-900/50 dark:text-white focus:ring-rose-500 focus:border-rose-500 transition-all text-sm"
                            placeholder="Ej: El activo es necesario en su ubicación actual hasta finalizar el inventario..."
                        ></textarea>
                    </div>
                </div>

                <div class="mt-8 flex justify-end gap-3">
                    <SecondaryButton @click="closeRejectionModal">
                        Cancelar
                    </SecondaryButton>
                    <button 
                        @click="submitRechazo"
                        :disabled="!rejectionForm.motivo_rechazo.trim() || processing"
                        class="px-6 py-2.5 bg-rose-600 hover:bg-rose-700 text-white text-sm font-black rounded-xl shadow-lg shadow-rose-600/20 transition-all disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2"
                    >
                        <Clock v-if="processing" class="w-4 h-4 animate-spin" />
                        Confirmar Rechazo
                    </button>
                </div>
            </div>
        </Modal>
    </AuthenticatedLayout>
</template>

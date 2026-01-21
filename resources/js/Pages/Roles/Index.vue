<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import Modal from '@/Components/Modal.vue';
import ConfirmationModal from '@/Components/ConfirmationModal.vue';
import TextInput from '@/Components/TextInput.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { 
    Plus, 
    Shield, 
    Settings, 
    X, 
    CheckCircle2, 
    AlertCircle,
    Fingerprint,
    Info,
    Trash2
} from 'lucide-vue-next';

const props = defineProps({
    roles: Object,
});

// Modal State
const showModal = ref(false);
const isEditing = ref(false);
const itemId = ref(null);
const confirmingDeletion = ref(false);

// Form
const form = useForm({
    name: '',
    description: '',
});

// Open/Close logic
const openCreateModal = () => {
    isEditing.value = false;
    itemId.value = null;
    form.reset();
    showModal.value = true;
};

const openEditModal = (role) => {
    isEditing.value = true;
    itemId.value = role.id;
    form.name = role.name;
    form.description = role.description;
    showModal.value = true;
};

const closeModal = () => {
    showModal.value = false;
    form.clearErrors();
};

const submitForm = () => {
    if (isEditing.value) {
        form.put(route('roles.update', itemId.value), {
            onSuccess: () => closeModal(),
        });
    } else {
        form.post(route('roles.store'), {
            onSuccess: () => closeModal(),
        });
    }
};

const openDeleteModal = (id) => {
    itemId.value = id;
    confirmingDeletion.value = true;
};

const deleteRole = () => {
    router.delete(route('roles.destroy', itemId.value), {
        onSuccess: () => confirmingDeletion.value = false,
    });
};
</script>

<template>
    <Head title="Gestión de Roles" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Control de Seguridad y Permisos</h2>
        </template>

        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-2 sm:p-4">
            <!-- Toolbar -->
            <div class="bg-gray-50 dark:bg-gray-900/50 p-4 rounded-xl border border-gray-100 dark:border-gray-700 mb-6 flex justify-between items-center">
                <div>
                   <h3 class="text-sm font-bold text-gray-700 dark:text-gray-300 flex items-center gap-2">
                       <Shield class="w-4 h-4 text-emerald-500" />
                       Roles y Funciones
                   </h3>
                </div>
                <button @click="openCreateModal" class="flex items-center gap-2 text-white bg-indigo-600 hover:bg-indigo-700 font-bold rounded-lg text-xs px-4 py-2 shadow-sm hover:shadow transition-all group">
                    <Plus class="w-4 h-4 group-hover:rotate-90 transition-transform" />
                    Nuevo Rol
                </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div v-for="role in (roles?.data || roles || [])" :key="role.id" class="group bg-white dark:bg-gray-900/40 rounded-2xl border border-gray-100 dark:border-gray-700 p-6 hover:border-indigo-200 dark:hover:border-indigo-900/50 hover:shadow-xl hover:shadow-indigo-500/5 transition-all duration-300">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center gap-4">
                            <div class="p-3 bg-indigo-50 dark:bg-indigo-900/30 rounded-xl border border-indigo-100 dark:border-indigo-800">
                                <Fingerprint class="w-6 h-6 text-indigo-500" />
                            </div>
                            <div>
                                <h4 class="font-extrabold text-gray-900 dark:text-white uppercase tracking-tight text-sm">{{ role.name }}</h4>
                                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mt-0.5">Identificador ID: {{ role.id }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="mb-6 h-12 overflow-hidden">
                        <p class="text-xs text-gray-500 dark:text-gray-400 line-clamp-2 italic">
                            {{ role.description || 'Sin descripción técnica disponible.' }}
                        </p>
                    </div>

                    <div class="flex items-center justify-end gap-2 pt-4 border-t border-gray-50 dark:border-gray-800">
                        <button @click="openEditModal(role)" class="flex items-center gap-2 px-3 py-1.5 text-xs font-bold text-emerald-600 bg-emerald-50 dark:bg-emerald-900/20 rounded-lg hover:bg-emerald-100 transition-all border border-emerald-100 dark:border-emerald-800/50">
                            <Settings class="w-3.5 h-3.5" /> Editar
                        </button>
                        <button @click="openDeleteModal(role.id)" class="flex items-center gap-2 px-3 py-1.5 text-xs font-bold text-red-600 bg-red-50 dark:bg-red-900/20 rounded-lg hover:bg-red-100 transition-all border border-red-100 dark:border-red-800/50">
                            <Trash2 class="w-3.5 h-3.5" /> Eliminar
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Create/Edit Modal -->
        <Modal :show="showModal" @close="closeModal" max-width="xl">
            <div class="p-8 bg-white dark:bg-gray-800 transition-all duration-300">
                <div class="flex items-center gap-4 mb-8">
                    <div class="p-3 bg-indigo-50 dark:bg-indigo-900/30 rounded-2xl">
                        <Shield class="w-8 h-8 text-indigo-600 dark:text-indigo-400" />
                    </div>
                    <div>
                        <h2 class="text-2xl font-extrabold text-gray-900 dark:text-white tracking-tight">
                            {{ isEditing ? 'Modificar Rol' : 'Registrar Nuevo Rol' }}
                        </h2>
                        <p class="text-xs text-gray-500 font-medium uppercase tracking-wider">Definición de Privilegios</p>
                    </div>
                </div>

                <form @submit.prevent="submitForm" class="space-y-6">
                    <div class="space-y-2">
                        <InputLabel value="Nombre del Rol" class="text-xs font-bold uppercase text-gray-400" />
                        <div class="relative">
                            <Fingerprint class="absolute left-4 top-3.5 w-4 h-4 text-gray-400" />
                            <TextInput 
                                v-model="form.name" 
                                type="text" 
                                class="w-full pl-11 border-gray-200 rounded-xl focus:ring-indigo-500 font-bold" 
                                placeholder="Ej: Administrador_Sistemas"
                            />
                        </div>
                        <InputError :message="form.errors.name" />
                    </div>

                    <div class="space-y-2">
                        <InputLabel value="Descripción de Funciones" class="text-xs font-bold uppercase text-gray-400" />
                        <textarea 
                            v-model="form.description" 
                            class="w-full border-gray-200 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-xl focus:ring-indigo-500 text-sm italic p-4 min-h-[100px]"
                            placeholder="Describa las responsabilidades y alcances de este rol..."
                        ></textarea>
                        <InputError :message="form.errors.description" />
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-6">
                        <SecondaryButton @click="closeModal" class="px-6 py-3 rounded-xl border-none hover:bg-red-50 hover:text-red-600 shadow-none text-gray-400 transition-all font-bold">
                            Cerrar
                        </SecondaryButton>
                        <PrimaryButton class="px-10 py-3 rounded-xl shadow-lg shadow-indigo-200 dark:shadow-none hover:translate-y-[-2px] transition-all font-bold" :disabled="form.processing">
                            <div class="flex items-center gap-2">
                                <CheckCircle2 v-if="!form.processing" class="w-4 h-4" />
                                <span>{{ isEditing ? 'Actualizar Rol' : 'Crear Rol Profesional' }}</span>
                            </div>
                        </PrimaryButton>
                    </div>
                </form>
            </div>
        </Modal>

        <!-- Delete Confirmation -->
        <ConfirmationModal :show="confirmingDeletion" @close="confirmingDeletion = false">
            <template #title>Atención: Eliminación de Rol</template>
            <template #content>¿Está seguro de eliminar este nivel de acceso? Los usuarios vinculados a este rol podrían perder privilegios críticos.</template>
            <template #footer>
                <SecondaryButton @click="confirmingDeletion = false" class="mr-3 font-bold">Mantener Rol</SecondaryButton>
                <DangerButton @click="deleteRole" class="bg-red-600 hover:bg-red-700 text-white px-6 py-2.5 rounded-xl font-bold">Confirmar Eliminación</DangerButton>
            </template>
        </ConfirmationModal>
    </AuthenticatedLayout>
</template>

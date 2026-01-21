<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
import Modal from '@/Components/Modal.vue';
import ConfirmationModal from '@/Components/ConfirmationModal.vue';
import TextInput from '@/Components/TextInput.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { 
    Plus, 
    User, 
    Mail, 
    Lock, 
    Shield, 
    X, 
    CheckCircle2, 
    AlertCircle,
    UserCircle,
    Settings,
    UserMinus
} from 'lucide-vue-next';

const props = defineProps({
    users: Object,
    roles: Array,
});

// Modal State
const showModal = ref(false);
const isEditing = ref(false);
const itemId = ref(null);
const confirmingDeletion = ref(false);

// Form
const form = useForm({
    name: '',
    email: '',
    password: '',
    roles: [],
});

// Open/Close logic
const openCreateModal = () => {
    isEditing.value = false;
    itemId.value = null;
    form.reset();
    showModal.value = true;
};

const openEditModal = (user) => {
    isEditing.value = true;
    itemId.value = user.id;
    form.name = user.name;
    form.email = user.email;
    form.password = '';
    form.roles = user.roles?.map(r => r.id) || [];
    showModal.value = true;
};

const closeModal = () => {
    showModal.value = false;
    form.clearErrors();
};

const submitForm = () => {
    if (isEditing.value) {
        form.put(route('users.update', itemId.value), {
            onSuccess: () => closeModal(),
        });
    } else {
        form.post(route('users.store'), {
            onSuccess: () => closeModal(),
        });
    }
};

const openDeleteModal = (id) => {
    itemId.value = id;
    confirmingDeletion.value = true;
};

const deleteUser = () => {
    router.delete(route('users.destroy', itemId.value), {
        onSuccess: () => confirmingDeletion.value = false,
    });
};
</script>

<template>
    <Head title="Gestión de Usuarios" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Control de Usuarios del Sistema</h2>
        </template>

        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-2 sm:p-4">
            <!-- Toolbar -->
            <div class="bg-gray-50 dark:bg-gray-900/50 p-4 rounded-xl border border-gray-100 dark:border-gray-700 mb-6 flex justify-between items-center">
                <div>
                   <h3 class="text-sm font-bold text-gray-700 dark:text-gray-300 flex items-center gap-2">
                       <User class="w-4 h-4 text-indigo-500" />
                       Colaboradores y Accesos
                   </h3>
                </div>
                <button @click="openCreateModal" class="flex items-center gap-2 text-white bg-indigo-600 hover:bg-indigo-700 font-bold rounded-lg text-xs px-4 py-2 shadow-sm hover:shadow transition-all group">
                    <Plus class="w-4 h-4 group-hover:rotate-90 transition-transform" />
                    Nuevo Usuario
                </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div v-for="user in (users?.data || users || [])" :key="user.id" class="relative group bg-white dark:bg-gray-900/40 rounded-2xl border border-gray-100 dark:border-gray-700 p-5 hover:border-indigo-200 dark:hover:border-indigo-900/50 hover:shadow-xl hover:shadow-indigo-500/5 transition-all duration-300">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-full bg-indigo-50 dark:bg-indigo-900/30 flex items-center justify-center border border-indigo-100 dark:border-indigo-800">
                                <UserCircle class="w-7 h-7 text-indigo-500" />
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-900 dark:text-white leading-tight">{{ user.name }}</h4>
                                <p class="text-[10px] text-gray-500 font-medium truncate w-32 md:w-40">{{ user.email }}</p>
                            </div>
                        </div>
                        <div class="flex gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                            <button @click="openEditModal(user)" class="p-2 text-gray-400 hover:text-emerald-600 hover:bg-emerald-50 dark:hover:bg-emerald-900/30 rounded-lg transition-all" title="Editar"><Settings class="w-4 h-4" /></button>
                            <button @click="openDeleteModal(user.id)" class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-900/30 rounded-lg transition-all" title="Eliminar"><UserMinus class="w-4 h-4" /></button>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <div class="flex flex-wrap gap-1.5">
                            <span v-for="role in (user.roles?.data || user.roles || [])" :key="role.id" class="px-2.5 py-0.5 bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600 dark:text-indigo-400 text-[10px] font-bold rounded-full border border-indigo-100 dark:border-indigo-800/50 uppercase tracking-tighter">
                                {{ role.name }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Create/Edit Modal -->
        <Modal :show="showModal" @close="closeModal" max-width="2xl">
            <div class="p-8 bg-white dark:bg-gray-800 transition-all duration-300">
                <div class="flex items-center gap-4 mb-8">
                    <div class="p-3 bg-indigo-50 dark:bg-indigo-900/30 rounded-2xl">
                        <UserCircle class="w-8 h-8 text-indigo-600 dark:text-indigo-400" />
                    </div>
                    <div>
                        <h2 class="text-2xl font-extrabold text-gray-900 dark:text-white tracking-tight">
                            {{ isEditing ? 'Editar Usuario' : 'Crear Usuario' }}
                        </h2>
                        <p class="text-xs text-gray-500 font-medium uppercase tracking-wider">Perfil y Accesos</p>
                    </div>
                </div>

                <form @submit.prevent="submitForm" class="space-y-6">
                    <div class="space-y-2">
                        <InputLabel value="Nombre Completo" class="text-xs font-bold uppercase text-gray-400" />
                        <div class="relative">
                            <User class="absolute left-4 top-3.5 w-4 h-4 text-gray-400" />
                            <TextInput 
                                v-model="form.name" 
                                type="text" 
                                class="w-full pl-11 border-gray-200 rounded-xl" 
                                placeholder="Nombre completo del colaborador"
                            />
                        </div>
                        <InputError :message="form.errors.name" />
                    </div>

                    <div class="space-y-2">
                        <InputLabel value="Correo Electrónico" class="text-xs font-bold uppercase text-gray-400" />
                        <div class="relative">
                            <Mail class="absolute left-4 top-3.5 w-4 h-4 text-gray-400" />
                            <TextInput 
                                v-model="form.email" 
                                type="email" 
                                class="w-full pl-11 border-gray-200 rounded-xl" 
                                placeholder="correo@ejemplo.com"
                            />
                        </div>
                        <InputError :message="form.errors.email" />
                    </div>

                    <div class="space-y-2">
                        <InputLabel :value="isEditing ? 'Nueva Contraseña (Opcional)' : 'Contraseña'" class="text-xs font-bold uppercase text-gray-400" />
                        <div class="relative">
                            <Lock class="absolute left-4 top-3.5 w-4 h-4 text-gray-400" />
                            <TextInput 
                                v-model="form.password" 
                                type="password" 
                                class="w-full pl-11 border-gray-200 rounded-xl" 
                                placeholder="********"
                            />
                        </div>
                        <InputError :message="form.errors.password" />
                    </div>

                    <div class="space-y-3">
                        <InputLabel value="Asignar Roles del Sistema" class="text-xs font-bold uppercase text-gray-400" />
                        <div class="grid grid-cols-2 gap-3">
                            <label v-for="role in (roles?.data || roles || [])" :key="role.id" class="flex items-center gap-3 p-3 rounded-xl border border-gray-100 dark:border-gray-700 cursor-pointer hover:bg-indigo-50 dark:hover:bg-indigo-900/20 transition-all group" :class="form.roles.includes(role.id) ? 'bg-indigo-50 border-indigo-200 dark:bg-indigo-900/30' : ''">
                                <input 
                                    type="checkbox" 
                                    :value="role.id" 
                                    v-model="form.roles" 
                                    class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500"
                                />
                                <span class="text-sm font-bold text-gray-700 dark:text-gray-300 capitalize">{{ role.name }}</span>
                            </label>
                        </div>
                        <InputError :message="form.errors.roles" />
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-6">
                        <SecondaryButton @click="closeModal" class="px-6 py-3 rounded-xl border-none hover:bg-red-50 hover:text-red-600 shadow-none text-gray-400 transition-all font-bold">
                            Cancelar
                        </SecondaryButton>
                        <PrimaryButton class="px-10 py-3 rounded-xl shadow-lg shadow-indigo-200 dark:shadow-none hover:translate-y-[-2px] transition-all font-bold" :disabled="form.processing">
                            <div class="flex items-center gap-2">
                                <CheckCircle2 v-if="!form.processing" class="w-4 h-4" />
                                <span>{{ isEditing ? 'Guardar Cambios' : 'Registrar Colaborador' }}</span>
                            </div>
                        </PrimaryButton>
                    </div>
                </form>
            </div>
        </Modal>

        <!-- Delete Confirmation -->
        <ConfirmationModal :show="confirmingDeletion" @close="confirmingDeletion = false">
            <template #title>Confirmar Eliminación</template>
            <template #content>¿Estás completamente seguro de eliminar este usuario? Se perderán todos sus permisos de acceso.</template>
            <template #footer>
                <SecondaryButton @click="confirmingDeletion = false" class="mr-3">Cancelar</SecondaryButton>
                <DangerButton @click="deleteUser" class="bg-red-600 hover:bg-red-700 text-white px-6 py-2.5 rounded-xl font-bold">Eliminar Definitivamente</DangerButton>
            </template>
        </ConfirmationModal>
    </AuthenticatedLayout>
</template>

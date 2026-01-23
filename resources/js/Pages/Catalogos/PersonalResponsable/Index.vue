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
import DangerButton from '@/Components/DangerButton.vue';

defineProps({ 
    responsables: Object,
    cargos: Array
});

const showingModal = ref(false);
const isEditing = ref(false);
const itemId = ref(null);

const form = useForm({
    nombre: '',
    cargo_id: '',
    numero_cedula: '',
    telefono: '',
    email: '',
    activo: true
});

const openCreateModal = () => {
    isEditing.value = false;
    itemId.value = null;
    form.reset();
    form.clearErrors();
    showingModal.value = true;
};

const openEditModal = (item) => {
    isEditing.value = true;
    itemId.value = item.id;
    form.nombre = item.nombre;
    form.cargo_id = item.cargo_id;
    form.numero_cedula = item.numero_cedula;
    form.telefono = item.telefono;
    form.email = item.email;
    form.activo = !!item.activo;
    form.clearErrors();
    showingModal.value = true;
};

const closeModal = () => {
    showingModal.value = false;
    form.reset();
};

const submitForm = () => {
    if (isEditing.value) {
        form.put(route('responsables.update', itemId.value), {
            onSuccess: () => closeModal(),
        });
    } else {
        form.post(route('responsables.store'), {
            onSuccess: () => closeModal(),
        });
    }
};

// Deletion Logic
const confirmingDeletion = ref(false);
const itemToDelete = ref(null);

const confirmDeletion = (id) => {
    itemToDelete.value = id;
    confirmingDeletion.value = true;
};

const deleteItem = () => {
    router.delete(route('responsables.destroy', itemToDelete.value), {
        onSuccess: () => {
            confirmingDeletion.value = false;
            itemToDelete.value = null;
        },
    });
};
</script>

<template>
    <Head title="Personal Responsable" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Personal Responsable</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="flex justify-end mb-6">
                        <button @click="openCreateModal" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                            Nuevo Responsable
                        </button>
                    </div>

                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-white uppercase bg-gradient-to-r from-blue-600 to-indigo-600">
                                <tr>
                                    <th scope="col" class="px-6 py-3">Nombre</th>
                                    <th scope="col" class="px-6 py-3">Cargo</th>
                                    <th scope="col" class="px-6 py-3">Cédula</th>
                                    <th scope="col" class="px-6 py-3 text-right">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="item in responsables.data" :key="item.id" class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                    <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ item.nombre }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ item.cargo_nombre || 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ item.numero_cedula || 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <button @click="openEditModal(item)" class="font-medium text-blue-600 dark:text-blue-500 hover:underline mr-3">Editar</button>
                                        <button @click="confirmDeletion(item.id)" class="font-medium text-red-600 dark:text-red-500 hover:underline">Eliminar</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Create/Edit Modal -->
        <Modal :show="showingModal" @close="closeModal">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                    {{ isEditing ? 'Editar Responsable' : 'Nuevo Responsable' }}
                </h2>

                <form @submit.prevent="submitForm" class="mt-6 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <InputLabel for="nombre" value="Nombre Completo" />
                            <TextInput
                                id="nombre"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="form.nombre"
                                required
                                autofocus
                            />
                            <InputError class="mt-2" :message="form.errors.nombre" />
                        </div>

                        <div>
                            <InputLabel for="cargo_id" value="Cargo" />
                            <select
                                id="cargo_id"
                                v-model="form.cargo_id"
                                class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                required
                            >
                                <option value="">Seleccione un cargo</option>
                                <option v-for="cargo in cargos" :key="cargo.id" :value="cargo.id">
                                    {{ cargo.nombre }}
                                </option>
                            </select>
                            <InputError class="mt-2" :message="form.errors.cargo_id" />
                        </div>

                        <div>
                            <InputLabel for="numero_cedula" value="Cédula" />
                            <TextInput
                                id="numero_cedula"
                                type="text"
                                class="mt-1 block w-full uppercase"
                                v-model="form.numero_cedula"
                                required
                                maxlength="14"
                                @input="form.numero_cedula = form.numero_cedula.toUpperCase().replace(/[^0-9A-Z]/g, '').slice(0, 14)"
                            />
                            <p v-if="form.numero_cedula.length > 0 && !/^\d{13}[A-Z]$/.test(form.numero_cedula)" class="text-xs text-yellow-600 dark:text-yellow-400 mt-1">
                                Formato: 13 números y 1 letra (Ej: 0012805800026A)
                            </p>
                            <InputError class="mt-2" :message="form.errors.numero_cedula" />
                        </div>

                        <div>
                            <InputLabel for="telefono" value="Teléfono" />
                            <TextInput
                                id="telefono"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="form.telefono"
                            />
                            <InputError class="mt-2" :message="form.errors.telefono" />
                        </div>

                        <div>
                            <InputLabel for="email" value="Email" />
                            <TextInput
                                id="email"
                                type="email"
                                class="mt-1 block w-full"
                                v-model="form.email"
                            />
                            <InputError class="mt-2" :message="form.errors.email" />
                        </div>
                    </div>

                    <div class="flex items-center justify-end">
                        <SecondaryButton @click="closeModal" class="mr-3">
                            Cancelar
                        </SecondaryButton>

                        <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                            {{ isEditing ? 'Actualizar' : 'Guardar' }}
                        </PrimaryButton>
                    </div>
                </form>
            </div>
        </Modal>

        <!-- Deletion Confirmation Modal -->
        <ConfirmationModal :show="confirmingDeletion" @close="confirmingDeletion = false">
            <template #title>
                Eliminar Responsable
            </template>

            <template #content>
                ¿Estás seguro de que deseas eliminar este responsable? Esta acción no se puede deshacer.
            </template>

            <template #footer>
                <DangerButton @click="deleteItem" class="ml-3">
                    Eliminar
                </DangerButton>

                <SecondaryButton @click="confirmingDeletion = false">
                    Cancelar
                </SecondaryButton>
            </template>
        </ConfirmationModal>
    </AuthenticatedLayout>
</template>



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

defineProps({ marcas: Object });

const showingModal = ref(false);
const isEditing = ref(false);
const itemId = ref(null);

const form = useForm({
    nombre: '',
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
    form.clearErrors();
    showingModal.value = true;
};

const closeModal = () => {
    showingModal.value = false;
    form.reset();
};

const submitForm = () => {
    if (isEditing.value) {
        form.put(route('marcas.update', itemId.value), {
            onSuccess: () => closeModal(),
        });
    } else {
        form.post(route('marcas.store'), {
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
    router.delete(route('marcas.destroy', itemToDelete.value), {
        onSuccess: () => {
            confirmingDeletion.value = false;
            itemToDelete.value = null;
        },
    });
};
</script>

<template>
    <Head title="Marcas" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Marcas</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="flex justify-end mb-6">
                        <button @click="openCreateModal" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                            Nueva Marca
                        </button>
                    </div>

                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-6 py-3">Nombre</th>
                                    <th scope="col" class="px-6 py-3 text-right">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="item in marcas.data" :key="item.id" class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                    <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ item.nombre }}
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
                    {{ isEditing ? 'Editar Marca' : 'Nueva Marca' }}
                </h2>

                <form @submit.prevent="submitForm" class="mt-6 space-y-6">
                    <div>
                        <InputLabel for="nombre" value="Nombre de la Marca" />
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
                Eliminar Marca
            </template>

            <template #content>
                ¿Estás seguro de que deseas eliminar esta marca? Esta acción no se puede deshacer.
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



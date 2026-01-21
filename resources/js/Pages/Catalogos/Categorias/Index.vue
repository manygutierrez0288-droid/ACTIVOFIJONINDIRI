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

defineProps({ categorias: Object });

const showingModal = ref(false);
const isEditing = ref(false);
const itemId = ref(null);

const form = useForm({
    codigo: '',
    nombre: '',
    descripcion: '',
    grupo_categoria: '',
    subcategoria: '',
    clase: '',
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
    form.codigo = item.codigo;
    form.nombre = item.nombre;
    form.descripcion = item.descripcion;
    form.grupo_categoria = item.grupo_categoria;
    form.subcategoria = item.subcategoria;
    form.clase = item.clase;
    form.clearErrors();
    showingModal.value = true;
};

const closeModal = () => {
    showingModal.value = false;
    form.reset();
};

const submitForm = () => {
    if (isEditing.value) {
        form.put(route('categorias.update', itemId.value), {
            onSuccess: () => closeModal(),
        });
    } else {
        form.post(route('categorias.store'), {
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
    router.delete(route('categorias.destroy', itemToDelete.value), {
        onSuccess: () => {
            confirmingDeletion.value = false;
            itemToDelete.value = null;
        },
    });
};
</script>

<template>
    <Head title="Categorías" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Categorías</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="flex justify-end mb-6">
                        <button @click="openCreateModal" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                            Nueva Categoría
                        </button>
                    </div>

                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-6 py-3">Código</th>
                                    <th scope="col" class="px-6 py-3">Categoría / Grupo</th>
                                    <th scope="col" class="px-6 py-3">Subcategoría</th>
                                    <th scope="col" class="px-6 py-3">Clase</th>
                                    <th scope="col" class="px-6 py-3">Descripción / Detalle</th>
                                    <th scope="col" class="px-6 py-3 text-right">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="item in categorias.data" :key="item.id" class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                    <td class="px-6 py-4 font-mono font-bold text-indigo-600">
                                        {{ item.codigo }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-medium text-gray-900 dark:text-white">{{ item.grupo_categoria }}</div>
                                        <div class="text-xs text-gray-500 italic">{{ item.nombre }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-xs">
                                        {{ item.subcategoria }}
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-blue-900 dark:text-blue-300">
                                            {{ item.clase }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ item.descripcion }}
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
                    {{ isEditing ? 'Editar Categoría' : 'Nueva Categoría' }}
                </h2>

                <form @submit.prevent="submitForm" class="mt-6 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <InputLabel for="codigo" value="Código de Activo" />
                            <TextInput
                                id="codigo"
                                type="text"
                                class="mt-1 block w-full font-mono"
                                v-model="form.codigo"
                                required
                                placeholder="Ej: 1000-1-01"
                            />
                            <InputError class="mt-2" :message="form.errors.codigo" />
                        </div>

                        <div>
                            <InputLabel for="nombre" value="Descripción del Bien" />
                            <TextInput
                                id="nombre"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="form.nombre"
                                required
                                placeholder="Ej: Terrenos urbanos"
                            />
                            <InputError class="mt-2" :message="form.errors.nombre" />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <InputLabel for="grupo_categoria" value="Categoría / Grupo" />
                            <TextInput
                                id="grupo_categoria"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="form.grupo_categoria"
                                placeholder="Ej: Terrenos"
                            />
                            <InputError class="mt-2" :message="form.errors.grupo_categoria" />
                        </div>

                        <div>
                            <InputLabel for="subcategoria" value="Subcategoría" />
                            <TextInput
                                id="subcategoria"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="form.subcategoria"
                                placeholder="Ej: Bienes Tangibles"
                            />
                            <InputError class="mt-2" :message="form.errors.subcategoria" />
                        </div>

                        <div>
                            <InputLabel for="clase" value="Clase" />
                            <TextInput
                                id="clase"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="form.clase"
                                placeholder="Ej: 1"
                            />
                            <InputError class="mt-2" :message="form.errors.clase" />
                        </div>
                    </div>

                    <div>
                        <InputLabel for="descripcion" value="Notas / Observaciones" />
                        <TextInput
                            id="descripcion"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="form.descripcion"
                        />
                        <InputError class="mt-2" :message="form.errors.descripcion" />
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
                Eliminar Categoría
            </template>

            <template #content>
                ¿Estás seguro de que deseas eliminar esta categoría? Esta acción no se puede deshacer.
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



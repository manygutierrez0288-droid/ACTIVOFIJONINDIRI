<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';
import TextInput from '@/Components/TextInput.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const props = defineProps({
    roles: Array
});

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    roles: [],
});

const submit = () => {
    form.post(route('users.store'));
};
</script>

<template>
    <Head title="Crear Usuario" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Crear Usuario</h2>
        </template>

        
            
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <form @submit.prevent="submit">
                        <div>
                            <InputLabel for="name" value="Nombre" />
                            <TextInput id="name" type="text" class="mt-1 block w-full" v-model="form.name" required autofocus />
                            <InputError class="mt-2" :message="form.errors.name" />
                        </div>

                        <div class="mt-4">
                            <InputLabel for="email" value="Correo Electrónico" />
                            <TextInput id="email" type="email" class="mt-1 block w-full" v-model="form.email" required />
                            <InputError class="mt-2" :message="form.errors.email" />
                        </div>

                        <div class="mt-4">
                            <InputLabel for="password" value="Contraseña" />
                            <TextInput id="password" type="password" class="mt-1 block w-full" v-model="form.password" required />
                            <InputError class="mt-2" :message="form.errors.password" />
                        </div>

                        <div class="mt-4">
                            <InputLabel for="password_confirmation" value="Confirmar Contraseña" />
                            <TextInput id="password_confirmation" type="password" class="mt-1 block w-full" v-model="form.password_confirmation" required />
                            <InputError class="mt-2" :message="form.errors.password_confirmation" />
                        </div>

                        <div class="mt-4">
                            <InputLabel value="Roles" />
                            <div class="flex flex-wrap gap-4 mt-2">
                                <label v-for="role in (roles?.data || roles || [])" :key="role.id" class="flex items-center space-x-2">
                                    <input type="checkbox" :value="role.id" v-model="form.roles" class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600">
                                    <span class="text-gray-700 dark:text-gray-300 text-sm">{{ role.name }}</span>
                                </label>
                            </div>
                             <InputError class="mt-2" :message="form.errors.roles" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <Link :href="route('users.index')" class="text-gray-600 dark:text-gray-400 underline text-sm hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 mr-4">
                                Cancelar
                            </Link>

                            <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                Guardar
                            </PrimaryButton>
                        </div>
                    </form>
                </div></AuthenticatedLayout>
</template>



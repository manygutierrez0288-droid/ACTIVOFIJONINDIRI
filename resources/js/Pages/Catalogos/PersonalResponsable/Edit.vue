<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';
import TextInput from '@/Components/TextInput.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const props = defineProps({ responsable: Object, cargos: Array });

const form = useForm({
    nombre: props.responsable.nombre,
    cargo_id: props.responsable.cargo_id,
    numero_cedula: props.responsable.numero_cedula,
    telefono: props.responsable.telefono,
    email: props.responsable.email,
    activo: props.responsable.activo
});

const submit = () => form.put(route('responsables.update', props.responsable.id));
</script>

<template>
    <Head title="Editar Personal" />
    <AuthenticatedLayout>
        <template #header><h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Editar Personal Responsable</h2></template>
        <div class="py-12"><div class="max-w-7xl mx-auto sm:px-6 lg:px-8"><div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
            <form @submit.prevent="submit">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2"><InputLabel for="nombre" value="Nombre Completo" /><TextInput id="nombre" class="mt-1 block w-full" v-model="form.nombre" required /><InputError :message="form.errors.nombre" /></div>
                    <div>
                        <InputLabel for="cargo_id" value="Cargo" />
                        <select id="cargo_id" v-model="form.cargo_id" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                            <option value="">Seleccione un cargo</option>
                            <option v-for="cargo in cargos" :key="cargo.id" :value="cargo.id">{{ cargo.nombre }}</option>
                        </select>
                        <InputError :message="form.errors.cargo_id" />
                    </div>
                    <div><InputLabel for="numero_cedula" value="Cédula" /><TextInput id="numero_cedula" class="mt-1 block w-full" v-model="form.numero_cedula" required /><InputError :message="form.errors.numero_cedula" /></div>
                    <div><InputLabel for="telefono" value="Teléfono" /><TextInput id="telefono" class="mt-1 block w-full" v-model="form.telefono" /><InputError :message="form.errors.telefono" /></div>
                    <div><InputLabel for="email" value="Email" /><TextInput id="email" type="email" class="mt-1 block w-full" v-model="form.email" /><InputError :message="form.errors.email" /></div>
                </div>
                <div class="flex justify-end mt-4"><Link :href="route('responsables.index')" class="mr-4 underline text-gray-600">Cancelar</Link><PrimaryButton :disabled="form.processing">Actualizar</PrimaryButton></div>
            </form>
        </div></div></div>
    </AuthenticatedLayout>
</template>

<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Iniciar Sesión" />

        <!-- Welcome Header -->
        <div class="text-center mb-10">
            <h2 class="text-3xl font-black text-gray-900 tracking-tight">
                ¡Bienvenido!
            </h2>
            <p class="mt-2 text-gray-500 font-medium">
                Ingresa tus credenciales para continuar
            </p>
        </div>

        <div v-if="status" class="mb-6 p-4 rounded-xl bg-green-50 text-sm font-medium text-green-700 border border-green-100 flex items-center shadow-sm">
            <svg class="w-5 h-5 mr-3 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            {{ status }}
        </div>

        <form @submit.prevent="submit" class="space-y-6">
            <!-- Email Field -->
            <div>
                <InputLabel for="email" value="Correo Electrónico" class="text-gray-700 font-bold mb-1 ml-1" />
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none transition-colors group-focus-within:text-indigo-600">
                        <svg class="h-5 w-5 text-gray-400 group-focus-within:text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                        </svg>
                    </div>
                    <TextInput
                        id="email"
                        type="email"
                        class="block w-full pl-12 pr-4 py-4 bg-gray-50 border-gray-200 text-gray-900 placeholder-gray-400 rounded-2xl focus:ring-2 focus:ring-indigo-500 focus:bg-white focus:border-transparent transition-all duration-300 shadow-sm outline-none"
                        v-model="form.email"
                        required
                        autofocus
                        autocomplete="username"
                        placeholder="ejemplo@nindiri.gob.ni"
                    />
                </div>
                <InputError class="mt-1 ml-1" :message="form.errors.email" />
            </div>

            <!-- Password Field -->
            <div>
                <div class="flex items-center justify-between mb-1 ml-1">
                    <InputLabel for="password" value="Contraseña" class="text-gray-700 font-bold" />
                    <Link
                        v-if="canResetPassword"
                        :href="route('password.request')"
                        class="text-xs text-indigo-600 hover:text-indigo-800 font-extrabold transition-colors"
                    >
                        ¿Olvidaste tu contraseña?
                    </Link>
                </div>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none transition-colors group-focus-within:text-indigo-600">
                        <svg class="h-5 w-5 text-gray-400 group-focus-within:text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    <TextInput
                        id="password"
                        type="password"
                        class="block w-full pl-12 pr-4 py-4 bg-gray-50 border-gray-200 text-gray-900 placeholder-gray-400 rounded-2xl focus:ring-2 focus:ring-indigo-500 focus:bg-white focus:border-transparent transition-all duration-300 shadow-sm outline-none"
                        v-model="form.password"
                        required
                        autocomplete="current-password"
                        placeholder="••••••••"
                    />
                </div>
                <InputError class="mt-1 ml-1" :message="form.errors.password" />
            </div>

            <!-- Remember Me -->
            <div class="flex items-center ml-1">
                <Checkbox 
                    name="remember" 
                    v-model:checked="form.remember"
                    class="rounded-lg border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 w-5 h-5"
                />
                <span class="ml-3 text-sm text-gray-600 font-semibold selection:bg-indigo-100">
                    Mantener sesión iniciada
                </span>
            </div>

            <!-- Submit Button -->
            <div class="pt-2">
                <button
                    type="submit"
                    :disabled="form.processing"
                    class="w-full flex justify-center items-center py-4 px-6 rounded-2xl text-white text-lg font-black bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 active:scale-[0.98] focus:outline-none focus:ring-2 focus:ring-indigo-500 shadow-xl shadow-indigo-100/50 transition-all duration-300 disabled:opacity-50 disabled:cursor-not-allowed uppercase tracking-wider"
                >
                    <svg v-if="form.processing" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span>{{ form.processing ? 'Verificando...' : 'Iniciar Sesión' }}</span>
                </button>
            </div>
        </form>

        <!-- Register Link -->
        <div class="mt-10 pt-6 border-t border-gray-100 text-center">
            <p class="text-sm text-gray-500 font-bold">
                ¿No tienes una cuenta?
                <Link
                    :href="route('register')"
                    class="ml-1 text-indigo-600 hover:text-indigo-800 transition-colors underline decoration-indigo-200 underline-offset-4"
                >
                    Regístrate aquí
                </Link>
            </p>
        </div>
    </GuestLayout>
</template>

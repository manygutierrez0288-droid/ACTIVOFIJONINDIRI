<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="min-h-screen flex">
        <!-- Left Panel - Decorative -->
        <div class="hidden lg:flex lg:w-1/2 relative overflow-hidden bg-gradient-to-br from-indigo-600 via-purple-600 to-pink-500">
            <!-- Animated Background Elements -->
            <div class="absolute inset-0 overflow-hidden">
                <div class="absolute -top-40 -right-40 w-80 h-80 bg-white/10 rounded-full blur-3xl animate-pulse"></div>
                <div class="absolute top-1/2 -left-20 w-60 h-60 bg-pink-400/20 rounded-full blur-2xl animate-pulse delay-1000"></div>
                <div class="absolute bottom-20 right-20 w-40 h-40 bg-indigo-400/20 rounded-full blur-xl animate-pulse delay-500"></div>
            </div>
            
            <!-- Content -->
            <div class="relative z-10 flex flex-col justify-center items-center w-full p-12 text-white">
                <div class="mb-8">
                    <svg class="w-24 h-24 text-white/90" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"></path>
                    </svg>
                </div>
                <h1 class="text-4xl font-bold mb-4 text-center">SIAFNIN</h1>
                <p class="text-xl text-white/80 text-center max-w-md">
                    Sistema Integral de Administración de Activos Fijos
                </p>
                <div class="mt-12 flex gap-4">
                    <div class="w-3 h-3 rounded-full bg-white/60"></div>
                    <div class="w-3 h-3 rounded-full bg-white/30"></div>
                    <div class="w-3 h-3 rounded-full bg-white/30"></div>
                </div>
            </div>
        </div>

        <!-- Right Panel - Form -->
        <div class="w-full lg:w-1/2 flex flex-col items-center justify-center bg-gray-50 dark:bg-gray-900 px-6 py-12">
            <!-- Mobile Logo -->
            <div class="lg:hidden mb-8">
                <Link href="/" class="flex flex-col items-center">
                    <div class="w-16 h-16 bg-gradient-to-br from-indigo-600 to-purple-600 rounded-2xl flex items-center justify-center shadow-lg shadow-indigo-500/30">
                        <svg class="w-10 h-10 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"></path>
                        </svg>
                    </div>
                    <span class="mt-3 text-xl font-bold text-gray-800 dark:text-white">SIAFNIN</span>
                </Link>
            </div>

            <!-- Form Container -->
            <div class="w-full max-w-md">
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl shadow-gray-200/50 dark:shadow-none border border-gray-100 dark:border-gray-700 p-8">
                    <slot />
                </div>
                
                <!-- Footer -->
                <p class="mt-8 text-center text-sm text-gray-500 dark:text-gray-400">
                    © {{ new Date().getFullYear() }} SIAFNIN. Todos los derechos reservados.
                </p>
            </div>
        </div>
    </div>
</template>

<style scoped>
@keyframes pulse {
    0%, 100% {
        opacity: 0.4;
        transform: scale(1);
    }
    50% {
        opacity: 0.6;
        transform: scale(1.05);
    }
}

.animate-pulse {
    animation: pulse 4s ease-in-out infinite;
}

.delay-500 {
    animation-delay: 0.5s;
}

.delay-1000 {
    animation-delay: 1s;
}
</style>

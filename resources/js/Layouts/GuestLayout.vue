<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="min-h-screen flex flex-col lg:flex-row overflow-hidden bg-white">
        <!-- Left Panel - Decorative -->
        <div class="hidden lg:flex lg:w-1/2 relative overflow-hidden">
            <!-- Background Image with Overlay -->
            <img 
                src="/images/login_bg.jpg" 
                alt="Fondo Nindirí" 
                class="absolute inset-0 w-full h-full object-cover scale-105" 
            />
            <div class="absolute inset-0 bg-gradient-to-b from-indigo-900/60 via-transparent to-indigo-950/75"></div>
            <div class="absolute inset-0 bg-slate-900/30"></div>
            
            <!-- Content -->
            <div class="relative z-10 flex flex-col justify-center items-center w-full p-12 text-center">
                <!-- Logo -->
                <div class="mb-10 animate-fade-in-down">
                    <img 
                        src="/images/logo_alcaldia_nindiri.png" 
                        alt="Alcaldía de Nindirí" 
                        class="w-56 h-auto drop-shadow-[0_0_20px_rgba(255,255,255,0.2)]" 
                    />
                </div>
                
                <h1 class="text-4xl font-black tracking-wider text-white uppercase drop-shadow-lg leading-tight mb-4 animate-fade-in-down delay-300">
                    Alcaldía Municipal<br/>
                    <span class="text-3xl">de Nindirí</span>
                </h1>
                
                <div class="w-20 h-1 bg-white/40 rounded-full mb-6 animate-scale-in delay-500"></div>
                
                <p class="text-xl text-indigo-50 font-medium tracking-wide drop-shadow-md animate-fade-in-up delay-700">
                    Sistema Integral de Administración de Activos Fijos
                </p>

                <!-- Carousel Visuals (specific to user request) -->
                <div class="absolute bottom-12 left-0 right-0 flex justify-center gap-3 animate-fade-in delay-1000">
                    <div class="w-3 h-3 rounded-full bg-white shadow-sm"></div>
                    <div class="w-3 h-3 rounded-full bg-white/30"></div>
                    <div class="w-3 h-3 rounded-full bg-white/30"></div>
                </div>
            </div>
        </div>

        <!-- Right Panel - Form -->
        <div class="flex-1 flex flex-col items-center justify-center bg-gray-50 p-6 md:p-12 relative">
            <!-- Mobile Header/Logo -->
            <div class="lg:hidden absolute top-8 left-0 right-0 flex flex-col items-center animate-fade-in-down">
                <img src="/images/logo_alcaldia_nindiri.png" alt="SIAFNIN" class="h-16 w-auto mb-2" />
                <span class="text-lg font-bold text-indigo-900">Alcaldía de Nindirí</span>
            </div>

            <!-- Form Card - "The Box" (El Cuadro) -->
            <div class="w-full max-w-md bg-white rounded-[2.5rem] shadow-2xl shadow-gray-200/50 border border-gray-100 p-8 md:p-12 animate-fade-in-up">
                <slot />
            </div>
            
            <!-- Footer -->
            <div class="mt-12 text-center animate-fade-in delay-1000">
                <p class="text-sm text-gray-400 font-medium">
                    © {{ new Date().getFullYear() }} SIAFNIN. Todos los derechos reservados.
                </p>
            </div>
        </div>
    </div>
</template>

<style scoped>
@keyframes fade-in-down {
    from { opacity: 0; transform: translateY(-30px); }
    to { opacity: 1; transform: translateY(0); }
}

@keyframes fade-in-up {
    from { opacity: 0; transform: translateY(30px); }
    to { opacity: 1; transform: translateY(0); }
}

@keyframes fade-in {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes scale-in {
    from { opacity: 0; transform: scaleX(0); }
    to { opacity: 1; transform: scaleX(1); }
}

.animate-fade-in-down { animation: fade-in-down 1.2s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
.animate-fade-in-up { animation: fade-in-up 1.2s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
.animate-fade-in { animation: fade-in 1.5s ease-out forwards; }
.animate-scale-in { animation: scale-in 1s cubic-bezier(0.16, 1, 0.3, 1) forwards; }

.delay-300 { animation-delay: 0.3s; opacity: 0; }
.delay-500 { animation-delay: 0.5s; opacity: 0; }
.delay-700 { animation-delay: 0.7s; opacity: 0; }
.delay-1000 { animation-delay: 1s; opacity: 0; }
</style>

<script setup>
import { ref } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { Link } from '@inertiajs/vue3';

const isSidebarOpen = ref(true);
const isCatalogosOpen = ref(false);
const showingNavigationDropdown = ref(false);

const toggleSidebar = () => {
    isSidebarOpen.value = !isSidebarOpen.value;
};
</script>

<template>
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900 flex">
        <!-- Sidebar -->
        <aside 
            :class="[
                'fixed inset-y-0 left-0 z-50 w-64 bg-indigo-900 text-indigo-100 transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-0',
                isSidebarOpen ? 'translate-x-0' : '-translate-x-full'
            ]"
        >
            <div class="flex flex-col h-full">
                <!-- Branding -->
                <div class="h-16 flex items-center px-6 bg-indigo-950/50">
                    <Link :href="route('dashboard')" class="flex items-center gap-3">
                        <ApplicationLogo class="h-8 w-8 fill-current text-white" />
                        <span class="text-xl font-bold tracking-wider text-white">SIAFNIN</span>
                    </Link>
                </div>

                <!-- Navigation Links -->
                <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-1 custom-scrollbar">
                    <div class="text-xs font-semibold text-indigo-400 uppercase tracking-widest px-3 mb-2">General</div>
                    
                    <Link :href="route('dashboard')" :class="[route().current('dashboard') ? 'bg-indigo-800 text-white' : 'hover:bg-indigo-800/50']" class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                        <span>Dashboard</span>
                    </Link>

                    <div class="pt-4 text-xs font-semibold text-indigo-400 uppercase tracking-widest px-3 mb-2">Operaciones</div>
                    <Link :href="route('activos.index')" :class="[route().current('activos.*') ? 'bg-indigo-800 text-white' : 'hover:bg-indigo-800/50']" class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                        <span>Activos Fijos</span>
                    </Link>

                    <!-- Catalogos Collapsible -->
                    <div class="pt-4 px-3">
                        <button 
                            @click="isCatalogosOpen = !isCatalogosOpen"
                            class="w-full h-8 flex items-center justify-between text-xs font-semibold text-indigo-400 uppercase tracking-widest hover:text-indigo-200 transition-colors"
                        >
                            <span>Catálogos</span>
                            <svg :class="{'rotate-180': isCatalogosOpen}" class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                    </div>
                    
                    <div v-show="isCatalogosOpen" class="space-y-1 mt-1 px-3">
                        <Link :href="route('departamentos.index')" class="flex items-center gap-3 px-3 py-1.5 rounded-lg hover:bg-indigo-800/50 text-sm transition-colors" :class="{'text-white font-medium': route().current('departamentos.*')}">Departamentos</Link>
                        <Link :href="route('ubicaciones.index')" class="flex items-center gap-3 px-3 py-1.5 rounded-lg hover:bg-indigo-800/50 text-sm transition-colors" :class="{'text-white font-medium': route().current('ubicaciones.*')}">Ubicaciones</Link>
                        <Link :href="route('categorias.index')" class="flex items-center gap-3 px-3 py-1.5 rounded-lg hover:bg-indigo-800/50 text-sm transition-colors" :class="{'text-white font-medium': route().current('categorias.*')}">Categorías</Link>
                        <Link :href="route('proveedores.index')" class="flex items-center gap-3 px-3 py-1.5 rounded-lg hover:bg-indigo-800/50 text-sm transition-colors" :class="{'text-white font-medium': route().current('proveedores.*')}">Proveedores</Link>
                        <Link :href="route('marcas.index')" class="flex items-center gap-3 px-3 py-1.5 rounded-lg hover:bg-indigo-800/50 text-sm transition-colors" :class="{'text-white font-medium': route().current('marcas.*')}">Marcas</Link>
                        <Link :href="route('modelos.index')" class="flex items-center gap-3 px-3 py-1.5 rounded-lg hover:bg-indigo-800/50 text-sm transition-colors" :class="{'text-white font-medium': route().current('modelos.*')}">Modelos</Link>
                        <Link :href="route('estados.index')" class="flex items-center gap-3 px-3 py-1.5 rounded-lg hover:bg-indigo-800/50 text-sm transition-colors" :class="{'text-white font-medium': route().current('estados.*')}">Estados</Link>
                        <Link :href="route('colores.index')" class="flex items-center gap-3 px-3 py-1.5 rounded-lg hover:bg-indigo-800/50 text-sm transition-colors" :class="{'text-white font-medium': route().current('colores.*')}">Colores</Link>
                        <Link :href="route('fuentes.index')" class="flex items-center gap-3 px-3 py-1.5 rounded-lg hover:bg-indigo-800/50 text-sm transition-colors" :class="{'text-white font-medium': route().current('fuentes.*')}">Fuentes</Link>
                        <Link :href="route('responsables.index')" class="flex items-center gap-3 px-3 py-1.5 rounded-lg hover:bg-indigo-800/50 text-sm transition-colors" :class="{'text-white font-medium': route().current('responsables.*')}">Responsables</Link>
                        <Link :href="route('cargos.index')" class="flex items-center gap-3 px-3 py-1.5 rounded-lg hover:bg-indigo-800/50 text-sm transition-colors" :class="{'text-white font-medium': route().current('cargos.*')}">Cargos</Link>
                        <Link :href="route('tecnicos.index')" class="flex items-center gap-3 px-3 py-1.5 rounded-lg hover:bg-indigo-800/50 text-sm transition-colors" :class="{'text-white font-medium': route().current('tecnicos.*')}">Técnicos</Link>
                    </div>

                    <div class="pt-4 text-xs font-semibold text-indigo-400 uppercase tracking-widest px-3 mb-2">Seguridad</div>
                    <Link :href="route('users.index')" :class="[route().current('users.*') ? 'bg-indigo-800 text-white' : 'hover:bg-indigo-800/50']" class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                        <span>Usuarios</span>
                    </Link>
                    <Link :href="route('roles.index')" :class="[route().current('roles.*') ? 'bg-indigo-800 text-white' : 'hover:bg-indigo-800/50']" class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        <span>Roles</span>
                    </Link>
                </nav>

                <!-- User Profile (Sidebar Bottom) -->
                <div class="p-4 border-t border-indigo-800 bg-indigo-950/30">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-indigo-700 flex items-center justify-center text-white font-bold">
                            {{ $page.props.auth.user.name.charAt(0) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-white truncate">{{ $page.props.auth.user.name }}</p>
                            <button @click="showingNavigationDropdown = !showingNavigationDropdown" class="text-xs text-indigo-300 hover:text-white transition-colors">Ver opciones</button>
                        </div>
                    </div>
                    
                    <div v-show="showingNavigationDropdown" class="mt-3 space-y-1">
                        <Link :href="route('profile.edit')" class="block px-3 py-1.5 text-sm text-indigo-200 hover:text-white hover:bg-indigo-800 rounded-lg">Perfil</Link>
                        <Link :href="route('logout')" method="post" as="button" class="w-full text-left block px-3 py-1.5 text-sm text-indigo-200 hover:text-white hover:bg-indigo-800 rounded-lg">Cerrar Sesión</Link>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            <!-- Header -->
            <header class="h-16 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between px-6 shrink-0">
                <button 
                    @click="isSidebarOpen = !isSidebarOpen"
                    class="p-2 -ml-2 text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors"
                >
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/></svg>
                </button>

                <!-- Page Heading (Mobile) -->
                <h2 v-if="$slots.header" class="lg:hidden text-lg font-semibold text-gray-800 dark:text-white truncate px-4">
                    <slot name="header" />
                </h2>

                <div class="flex items-center gap-4">
                    <!-- Notifications/Search placeholder -->
                    <div class="hidden sm:flex items-center gap-2">
                        <button class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </button>
                        <button class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                        </button>
                    </div>
                </div>
            </header>

            <!-- Content Area -->
            <main class="flex-1 overflow-y-auto bg-gray-50 dark:bg-gray-900 custom-scrollbar">
                <!-- Page Heading (Desktop) -->
                <div v-if="$slots.header" class="hidden lg:block px-8 pt-8 pb-4">
                    <div class="max-w-7xl mx-auto">
                        <h2 class="text-3xl font-bold text-gray-900 dark:text-white">
                            <slot name="header" />
                        </h2>
                    </div>
                </div>

                <div class="py-8">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <slot />
                    </div>
                </div>
            </main>
        </div>

        <!-- Mobile Overlay -->
        <div 
            v-show="isSidebarOpen" 
            @click="isSidebarOpen = false"
            class="lg:hidden fixed inset-0 z-40 bg-gray-900/50 backdrop-blur-sm transition-opacity"
        ></div>
    </div>
</template>

<style>
.custom-scrollbar::-webkit-scrollbar {
    width: 4px;
}
.custom-scrollbar::-webkit-scrollbar-track {
    background: transparent;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    background: rgba(255, 255, 255, 0.1);
    border-radius: 10px;
}
.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: rgba(255, 255, 255, 0.2);
}
</style>

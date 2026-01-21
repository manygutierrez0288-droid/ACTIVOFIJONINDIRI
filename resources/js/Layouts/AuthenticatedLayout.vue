<script setup>
import { ref, watch, computed } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import Toast from '@/Components/Toast.vue';
import { Link, usePage } from '@inertiajs/vue3';
import GlobalSearch from '@/Components/GlobalSearch.vue';
import NotificationDropdown from '@/Components/NotificationDropdown.vue';

const isSidebarOpen = ref(false);
const isCatalogosOpen = ref(route().current('departamentos.*') || route().current('ubicaciones.*') || route().current('categorias.*') || route().current('proveedores.*') || route().current('marcas.*') || route().current('modelos.*') || route().current('estados.*') || route().current('colores.*') || route().current('fuentes.*') || route().current('responsables.*') || route().current('cargos.*') || route().current('tecnicos.*'));
const isActivosOpen = ref(route().current('activos.*') || route().current('vehiculos.*') || route().current('terrenos.*'));
const showingNavigationDropdown = ref(false);

const toasts = ref([]);
let toastIdCounter = 0;

const addToast = (message, type = 'success') => {
    const id = ++toastIdCounter;
    toasts.value.push({ id, message, type });
    console.log('Toast added:', { id, message, type });
};

const removeToast = (id) => {
    toasts.value = toasts.value.filter(t => t.id !== id);
    console.log('Toast removed:', id);
};

// Watch flash messages directly
// Watch flash messages directly
const page = usePage();

watch(() => page.props.flash, (flash) => {
    // Basic check for existence. You might want to implement a unique ID from backend 
    // to prevent duplicates, but for now this ensures visibility.
    if (flash?.success) {
        addToast(flash.success, 'success');
        // Clear flash to prevent reappearance on subsequent potential non-nav updates
        // Note: Direct prop mutation isn't ideal but works for local state handling or just rely on new navigation clearing it.
    }
    if (flash?.error) {
        addToast(flash.error, 'error');
    }
}, { deep: true }); // Removed immediate:true to prevent double-toast on mount if not needed, or keep it if navigation preserves component.

// Just in case, check on mount explicitly
import { onMounted } from 'vue';
onMounted(() => {
    if (page.props.flash?.success) {
        addToast(page.props.flash.success, 'success');
    }
    if (page.props.flash?.error) {
        addToast(page.props.flash.error, 'error');
    }

    // Listener para eventos globales de toast
    window.addEventListener('toast', (e) => {
        addToast(e.detail.message, e.detail.type || 'success');
    });
});

const toggleSidebar = () => {
    isSidebarOpen.value = !isSidebarOpen.value;
};

const hasPermission = (permissionNames) => {
    const permissions = Array.isArray(permissionNames) ? permissionNames : [permissionNames];
    return page.props.auth.user.permissions.some(p => permissions.includes(p));
};

const hasRole = (roleNames) => {
    const roles = Array.isArray(roleNames) ? roleNames : [roleNames];
    return page.props.auth.user.roles.some(r => roles.includes(r));
};

const isAdmin = computed(() => hasRole(['Administrador', 'admin']));
</script>

<template>
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900 flex">
        <!-- Sidebar -->
        <aside 
            :class="[
                'fixed inset-y-0 left-0 z-50 w-72 bg-indigo-900 text-indigo-100 transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-0 print:hidden',
                isSidebarOpen ? 'translate-x-0' : '-translate-x-full'
            ]"
        >
            <div class="flex flex-col h-full">
                <!-- Branding -->
                <div class="h-20 flex items-center px-6 bg-indigo-950/40 border-b border-white/5">
                    <Link :href="route('dashboard')" class="flex items-center gap-3 group">
                        <div class="p-2 rounded-xl bg-indigo-600/20 group-hover:bg-indigo-500/30 transition-all duration-300 shadow-inner">
                            <ApplicationLogo class="h-7 w-7 fill-current text-white transform group-hover:scale-110 transition-transform" />
                        </div>
                        <span class="text-xl font-black tracking-tighter text-white">SIAFNIN</span>
                    </Link>
                </div>

                <!-- Navigation Links -->
                <nav class="flex-1 overflow-y-auto py-8 px-5 space-y-8 custom-scrollbar">
                    <!-- Section: Core -->
                    <div>
                        <div class="text-[11px] font-bold text-indigo-400/60 uppercase tracking-[0.2em] px-3 mb-4">Principal</div>
                        <div class="space-y-2">
                            <Link :href="route('dashboard')" 
                                :class="[route().current('dashboard') ? 'nav-item-active text-white font-bold' : 'text-indigo-200/70 hover:bg-white/5 hover:text-white']" 
                                class="group flex items-center gap-4 px-4 py-3 rounded-xl transition-all duration-300"
                            >
                                <div class="p-2 rounded-xl bg-indigo-800/20 group-hover:bg-indigo-700/30 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                                </div>
                                <span class="text-[15px]">Dashboard</span>
                            </Link>
                        </div>
                    </div>

                    <!-- Section: System -->
                    <div v-if="hasPermission('catalogos')">
                        <div class="text-[11px] font-bold text-indigo-400/60 uppercase tracking-[0.2em] px-3 mb-4">Configuración</div>
                        <div class="space-y-2">
                            <!-- Catalogos Collapsible -->
                            <div class="relative">
                                <button 
                                    @click="isCatalogosOpen = !isCatalogosOpen"
                                    class="w-full flex items-center justify-between gap-4 px-4 py-3 rounded-xl transition-all duration-300 group"
                                    :class="[isCatalogosOpen ? 'text-white font-bold' : 'text-indigo-200/70 hover:bg-white/5 hover:text-white']"
                                >
                                    <div class="flex items-center gap-4">
                                        <div class="p-2 rounded-xl bg-indigo-800/20 group-hover:bg-indigo-700/30 transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/></svg>
                                        </div>
                                        <span class="text-[15px]">Catálogos</span>
                                    </div>
                                    <svg :class="{'rotate-180': isCatalogosOpen}" class="w-4 h-4 transition-transform duration-300 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                </button>
                                
                                <div v-show="isCatalogosOpen" class="relative mt-2 ml-5 border-l border-white/10 grid grid-cols-1 gap-1 py-1">
                                    <Link v-for="link in [
                                        { route: 'departamentos.index', label: 'Departamentos' },
                                        { route: 'ubicaciones.index', label: 'Ubicaciones' },
                                        { route: 'categorias.index', label: 'Categorías' },
                                        { route: 'proveedores.index', label: 'Proveedores' },
                                        { route: 'marcas.index', label: 'Marcas' },
                                        { route: 'modelos.index', label: 'Modelos' },
                                        { route: 'estados.index', label: 'Estados' },
                                        { route: 'colores.index', label: 'Colores' },
                                        { route: 'fuentes.index', label: 'Fuentes' },
                                        { route: 'responsables.index', label: 'Responsables' },
                                        { route: 'cargos.index', label: 'Cargos' },
                                        { route: 'tecnicos.index', label: 'Técnicos' }
                                    ]" :key="link.route" :href="route(link.route)" class="flex items-center gap-4 px-5 py-2 rounded-lg hover:bg-white/5 text-sm transition-all duration-200" :class="route().current(link.route.split('.')[0] + '.*') ? 'text-white font-bold bg-white/5' : 'text-indigo-300 hover:text-white'">
                                        <div class="sub-nav-dot"></div>
                                        <span>{{ link.label }}</span>
                                    </Link>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Section: Assets -->
                    <div>
                        <div v-if="hasPermission(['activos', 'vehiculos', 'terrenos'])" class="text-[11px] font-bold text-indigo-400/60 uppercase tracking-[0.2em] px-3 mb-4">Gestión de Activos</div>
                        <div class="space-y-2">
                            <!-- Activos Fijos Collapsible -->
                            <div v-if="hasPermission(['activos', 'vehiculos', 'terrenos'])" class="relative">
                                <button 
                                    @click="isActivosOpen = !isActivosOpen"
                                    class="w-full flex items-center justify-between gap-4 px-4 py-3 rounded-xl transition-all duration-300 group"
                                    :class="[(route().current('activos.*') || route().current('vehiculos.*') || route().current('terrenos.*')) ? 'nav-item-active text-white font-bold shadow-sm' : 'text-indigo-200/70 hover:bg-white/5 hover:text-white']"
                                >
                                    <div class="flex items-center gap-4">
                                        <div class="p-2 rounded-xl bg-indigo-800/20 group-hover:bg-indigo-700/30 transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                        </div>
                                        <span class="text-[15px]">Activos Fijos</span>
                                    </div>
                                    <svg :class="{'rotate-180': isActivosOpen}" class="w-4 h-4 transition-transform duration-300 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                </button>

                                <div v-show="isActivosOpen" class="relative mt-2 ml-5 border-l border-white/10 space-y-1 py-1">
                                    <Link :href="route('activos.index')" class="flex items-center gap-4 px-5 py-2.5 rounded-lg hover:bg-white/5 text-sm transition-all duration-200" :class="route().current('activos.index') ? 'text-white font-extrabold bg-white/5' : 'text-indigo-300 hover:text-white'">
                                        <div class="sub-nav-dot"></div>
                                        <span>Listar Activos</span>
                                    </Link>
                                    <Link :href="route('movimientos.pending')" class="flex items-center gap-4 px-5 py-2.5 rounded-lg hover:bg-white/5 text-sm transition-all duration-200" :class="route().current('movimientos.pending') ? 'text-white font-extrabold bg-white/5' : 'text-indigo-300 hover:text-white'">
                                        <div class="sub-nav-dot"></div>
                                        <span>Traslados Pendientes</span>
                                    </Link>
                                    <Link :href="route('vehiculos.index')" class="flex items-center gap-4 px-5 py-2.5 rounded-lg hover:bg-white/5 text-sm transition-all duration-200" :class="route().current('vehiculos.*') ? 'text-white font-extrabold bg-white/5' : 'text-indigo-300 hover:text-white'">
                                        <div class="sub-nav-dot"></div>
                                        <span>Vehículos</span>
                                    </Link>
                                    <Link :href="route('terrenos.index')" class="flex items-center gap-4 px-5 py-2.5 rounded-lg hover:bg-white/5 text-sm transition-all duration-200" :class="route().current('terrenos.*') ? 'text-white font-extrabold bg-white/5' : 'text-indigo-300 hover:text-white'">
                                        <div class="sub-nav-dot"></div>
                                        <span>Terrenos</span>
                                    </Link>
                                </div>
                            </div>

                            <Link v-if="hasPermission('reportes')" :href="route('reportes.index')" 
                                :class="[route().current('reportes.*') ? 'nav-item-active text-white font-bold' : 'text-indigo-200/70 hover:bg-white/5 hover:text-white']" 
                                class="group flex items-center gap-4 px-4 py-3 rounded-xl transition-all duration-300"
                            >
                                <div class="p-2 rounded-xl bg-indigo-800/20 group-hover:bg-indigo-700/30 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                </div>
                                <span class="text-[15px]">Reportes</span>
                            </Link>
                        </div>
                    </div>

                    <!-- Section: Security -->
                    <div v-if="hasPermission('usuarios')">
                        <div class="text-[11px] font-bold text-indigo-400/60 uppercase tracking-[0.2em] px-3 mb-4">Seguridad</div>
                        <div class="space-y-2">
                            <Link :href="route('users.index')" :class="[route().current('users.*') ? 'nav-item-active text-white font-bold' : 'text-indigo-200/70 hover:bg-white/5 hover:text-white']" class="group flex items-center gap-4 px-4 py-3 rounded-xl transition-all duration-300">
                                <div class="p-2 rounded-xl bg-indigo-800/20 group-hover:bg-indigo-700/30 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                </div>
                                <span class="text-[15px]">Usuarios</span>
                            </Link>
                            
                            <Link :href="route('roles.index')" :class="[route().current('roles.*') ? 'nav-item-active text-white font-bold' : 'text-indigo-200/70 hover:bg-white/5 hover:text-white']" class="group flex items-center gap-4 px-4 py-3 rounded-xl transition-all duration-300">
                                <div class="p-2 rounded-xl bg-indigo-800/20 group-hover:bg-indigo-700/30 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                </div>
                                <span class="text-[15px]">Roles</span>
                            </Link>
                        </div>
                    </div>
                </nav>

            </div>
        </aside>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col min-w-0">
            <!-- Header -->
            <header class="relative z-30 lg:z-[60] h-16 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between px-6 shrink-0 print:hidden">
                <button 
                    @click="isSidebarOpen = !isSidebarOpen"
                    class="p-2 -ml-2 text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors lg:hidden"
                >
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/></svg>
                </button>

                <!-- Page Heading (Mobile) -->
                <h2 v-if="$slots.header" class="lg:hidden text-lg font-semibold text-gray-800 dark:text-white truncate px-4">
                    <slot name="header" />
                </h2>

                <div class="flex items-center gap-6 ml-auto">
                    <!-- Notifications/Search placeholder -->
                    <div class="hidden sm:flex items-center gap-4 w-full max-w-md border-r border-gray-100 dark:border-gray-700 pr-4">
                        <GlobalSearch />
                        <NotificationDropdown />
                    </div>

                    <!-- Top Bar User Profile -->
                    <div class="flex items-center">
                        <Dropdown align="right" width="48">
                            <template #trigger>
                                <button type="button" class="flex items-center gap-3 p-1 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-700 transition-all focus:outline-none group">
                                    <div class="w-9 h-9 rounded-lg bg-indigo-600 dark:bg-indigo-500 shadow-md shadow-indigo-200 dark:shadow-none flex items-center justify-center text-white font-bold text-sm transition-transform group-hover:scale-105">
                                        {{ $page.props.auth.user.name.charAt(0).toUpperCase() }}
                                    </div>
                                    <div class="hidden md:block text-left">
                                        <p class="text-xs font-bold text-gray-700 dark:text-gray-200 leading-none mb-0.5">{{ $page.props.auth.user.name }}</p>
                                        <p class="text-[10px] text-gray-400 font-medium leading-none">{{ $page.props.auth.user.roles.join(', ') || 'Usuario' }}</p>
                                    </div>
                                    <svg class="w-4 h-4 text-gray-400 group-hover:text-indigo-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                            </template>

                            <template #content>
                                <div class="px-4 py-3 bg-gray-50 dark:bg-gray-700/50 border-b border-gray-100 dark:border-gray-600">
                                    <p class="text-xs text-gray-500 dark:text-gray-400 uppercase font-bold tracking-widest">Cuenta</p>
                                </div>
                                <DropdownLink :href="route('profile.edit')">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                                        <span>Mi Perfil</span>
                                    </div>
                                </DropdownLink>
                                <DropdownLink :href="route('logout')" method="post" as="button">
                                    <div class="flex items-center gap-2 text-red-600 dark:text-red-400">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                                        <span>Cerrar Sesión</span>
                                    </div>
                                </DropdownLink>
                            </template>
                        </Dropdown>
                    </div>
                </div>
            </header>

            <!-- Toasts Container -->
            <div aria-live="assertive" class="fixed inset-0 flex items-end px-4 py-6 pointer-events-none sm:p-6 sm:items-start z-[9999]">
                <div class="w-full flex flex-col items-center space-y-4 sm:items-end">
                    <Toast 
                        v-for="toast in toasts" 
                        :key="toast.id" 
                        :message="toast.message" 
                        :type="toast.type" 
                        @close="removeToast(toast.id)" 
                    />
                </div>
            </div>

            <!-- Content Area -->
            <main class="flex-1 overflow-y-auto bg-gray-50 dark:bg-gray-900 custom-scrollbar">
                <!-- Page Heading (Desktop) -->
                <div v-if="$slots.header" class="hidden lg:block px-8 pt-4 pb-2">
                    <div class="max-w-full mx-auto">
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                            <slot name="header" />
                        </h2>
                    </div>
                </div>

                <div class="py-4">
                    <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8">
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
/* Force scrollbar to always show */
.custom-scrollbar {
    overflow-y: scroll !important;
    scrollbar-width: thin;
    scrollbar-color: rgba(255, 255, 255, 0.2) rgba(0, 0, 0, 0.1);
}

.custom-scrollbar::-webkit-scrollbar {
    width: 10px;
}
.custom-scrollbar::-webkit-scrollbar-track {
    background: rgba(0, 0, 0, 0.15);
    border-radius: 10px;
    margin: 8px 0;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    background: rgba(255, 255, 255, 0.25);
    border-radius: 10px;
    border: 2px solid rgba(0, 0, 0, 0.15);
}
.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: rgba(255, 255, 255, 0.4);
}

/* Glassmorphism & Professional Nav Styles */
.nav-item-active {
    background: linear-gradient(90deg, rgba(79, 70, 229, 0.2) 0%, rgba(79, 70, 229, 0) 100%);
    position: relative;
    color: white !important;
}

.nav-item-active::before {
    content: '';
    position: absolute;
    left: 0;
    top: 20%;
    bottom: 20%;
    width: 3px;
    background: #6366f1;
    border-radius: 0 4px 4px 0;
    box-shadow: 0 0 10px rgba(99, 102, 241, 0.5);
}

.sub-nav-indicator {
    position: absolute;
    left: 20px;
    top: 0;
    bottom: 0;
    width: 1px;
    background: rgba(255, 255, 255, 0.1);
}

.sub-nav-dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    border: 1.5px solid rgba(255, 255, 255, 0.3);
    transition: all 0.3s ease;
}

.nav-item-active .sub-nav-dot {
    background: #6366f1;
    border-color: #6366f1;
    box-shadow: 0 0 8px rgba(99, 102, 241, 0.6);
}
</style>

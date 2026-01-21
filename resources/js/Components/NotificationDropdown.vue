<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import { Link } from '@inertiajs/vue3';
import axios from 'axios';

const notifications = ref([]);
const unreadCount = ref(0);
const isOpen = ref(false);
const loading = ref(false);
let pollInterval;

const fetchNotifications = async (isFirstLoad = false) => {
    loading.value = isFirstLoad;
    try {
        const response = await axios.get('/notifications');
        notifications.value = response.data.notifications;
        unreadCount.value = response.data.unreadCount;
    } catch (error) {
        console.error('Error fetching notifications:', error);
    } finally {
        loading.value = false;
    }
};

const toggleDropdown = (e) => {
    if (e) e.stopPropagation();
    isOpen.value = !isOpen.value;
    if (isOpen.value) {
        fetchNotifications(true);
    }
};

const closeDropdown = (e) => {
    if (isOpen.value && e && e.target && !e.target.closest('.notification-container')) {
        isOpen.value = false;
    }
};

const markAsRead = async (id) => {
    try {
        await axios.post(`/notifications/${id}/read`);
        const notif = notifications.value.find(n => n.id === id);
        if (notif && !notif.read_at) {
            notif.read_at = new Date().toISOString();
            unreadCount.value = Math.max(0, unreadCount.value - 1);
        }
    } catch (error) {
        console.error('Error marking as read:', error);
    }
};

const markAllAsRead = async () => {
    try {
        await axios.post('/notifications/read-all');
        notifications.value.forEach(n => {
            if (!n.read_at) n.read_at = new Date().toISOString();
        });
        unreadCount.value = 0;
    } catch (error) {
        console.error('Error marking all as read:', error);
    }
};

const formatTime = (dateString) => {
    const date = new Date(dateString);
    return date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
};

const getIcon = (type) => {
    switch (type) {
        case 'activo_created': return '📦';
        case 'activo_assigned': return '👤';
        case 'mantenimiento_created': return '🛠️';
        case 'baja_created': return '🚫';
        case 'movimiento_created': return '🔄';
        case 'mantenimiento_pendiente': return '⚠️';
        default: return '🔔';
    }
};

onMounted(() => {
    fetchNotifications(true);
    window.addEventListener('click', closeDropdown);
    pollInterval = setInterval(() => fetchNotifications(false), 30000);
});

onUnmounted(() => {
    window.removeEventListener('click', closeDropdown);
    clearInterval(pollInterval);
});
</script>

<template>
    <div class="relative notification-container">
        <!-- Bell Button -->
        <button 
            @click="toggleDropdown"
            class="p-2 text-gray-400 hover:text-indigo-600 dark:text-gray-400 dark:hover:text-indigo-400 transition-all relative focus:outline-none z-30"
            type="button"
        >
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
            </svg>
            
            <span 
                v-if="unreadCount > 0"
                class="absolute top-1.5 right-1.5 w-4 h-4 bg-red-500 text-white text-[10px] font-bold flex items-center justify-center rounded-full border-2 border-white dark:border-gray-800 animate-pulse"
            >
                {{ unreadCount > 9 ? '9+' : unreadCount }}
            </span>
        </button>

        <!-- Dropdown Panel -->
        <div 
            v-if="isOpen"
            @click.stop
            class="absolute right-0 mt-3 w-80 bg-white dark:bg-gray-800 rounded-xl shadow-[0_10px_40px_rgba(0,0,0,0.2)] border border-gray-100 dark:border-gray-700 z-[100] overflow-hidden transform origin-top-right transition-all duration-200"
        >
            <!-- Header -->
            <div class="p-4 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center bg-gray-50/50 dark:bg-gray-700/50">
                <h3 class="font-bold text-gray-800 dark:text-white text-sm">Notificaciones</h3>
                <button 
                    v-if="unreadCount > 0"
                    @click="markAllAsRead"
                    class="text-[10px] text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 font-bold uppercase transition-colors"
                >
                    Marcar todo como leído
                </button>
            </div>

            <!-- List -->
            <div class="max-h-[400px] overflow-y-auto custom-scrollbar">
                <div v-if="loading && notifications.length === 0" class="p-8 text-center text-gray-500 text-xs">
                    Cargando...
                </div>
                
                <div v-else-if="notifications.length === 0" class="p-10 text-center">
                    <p class="text-gray-900 dark:text-white font-medium text-sm">Sin notificaciones</p>
                </div>

                <div 
                    v-for="notification in notifications" 
                    :key="notification.id"
                    @click="markAsRead(notification.id)"
                    :class="['p-4 border-b border-gray-50 dark:border-gray-700/50 hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-all cursor-pointer', !notification.read_at ? 'bg-indigo-50/30 dark:bg-indigo-900/10' : 'opacity-70']"
                >
                    <div class="flex gap-3">
                        <span class="text-xl shrink-0">{{ getIcon(notification.type) }}</span>
                        <div class="flex-1 min-w-0">
                            <p :class="['text-xs leading-snug mb-1', !notification.read_at ? 'font-bold text-gray-900 dark:text-white' : 'text-gray-600 dark:text-gray-300']">
                                {{ notification.title }}
                            </p>
                            <p class="text-[11px] text-gray-500 dark:text-gray-400 line-clamp-2 leading-relaxed">
                                {{ notification.message }}
                            </p>
                            <span class="text-[9px] text-gray-400 mt-1.5 block uppercase">
                                {{ formatTime(notification.created_at) }}
                            </span>
                        </div>
                        <div v-if="!notification.read_at" class="w-1.5 h-1.5 bg-indigo-500 rounded-full shrink-0"></div>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="p-3 border-t border-gray-100 dark:border-gray-700 bg-gray-50/30 dark:bg-gray-700/30 text-center">
                <Link href="#" class="text-[10px] font-bold text-gray-500 hover:text-indigo-600 dark:text-gray-400 uppercase tracking-widest transition-colors">
                    Ver todas
                </Link>
            </div>
        </div>
    </div>
</template>

<style scoped>
.custom-scrollbar::-webkit-scrollbar {
    width: 4px;
}
.custom-scrollbar::-webkit-scrollbar-track {
    background: transparent;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    background: rgba(156, 163, 175, 0.2);
    border-radius: 10px;
}
</style>

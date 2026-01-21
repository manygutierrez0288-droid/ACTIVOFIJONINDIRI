<script setup>
import { ref, watch } from 'vue';
import { router } from '@inertiajs/vue3';
import debounce from 'lodash/debounce';
import axios from 'axios';
import { Search, Loader2, Box, Truck, ChevronRight } from 'lucide-vue-next';

const query = ref('');
const results = ref([]);
const isLoading = ref(false);
const showResults = ref(false);
const searchInput = ref(null);
const selectedIndex = ref(-1);

const icons = {
    Box,
    Truck
};

const performSearch = debounce(async (val) => {
    if (!val || val.length < 2) {
        results.value = [];
        return;
    }

    isLoading.value = true;
    try {
        const response = await axios.get(route('global.search', { q: val }));
        results.value = response.data;
        selectedIndex.value = -1; // Reset selection
    } catch (error) {
        console.error('Search error:', error);
        results.value = [];
    } finally {
        isLoading.value = false;
        showResults.value = true;
    }
}, 300);

watch(query, (val) => {
    performSearch(val);
});

const handleSelect = (result) => {
    if (!result) return;
    router.visit(result.url);
    closeSearch();
};

const closeSearch = () => {
    showResults.value = false;
    query.value = '';
    results.value = [];
};

const onKeydown = (e) => {
    if (!showResults.value) return;

    switch (e.key) {
        case 'ArrowDown':
            e.preventDefault();
            selectedIndex.value = (selectedIndex.value + 1) % results.value.length;
            break;
        case 'ArrowUp':
            e.preventDefault();
            selectedIndex.value = (selectedIndex.value - 1 + results.value.length) % results.value.length;
            break;
        case 'Enter':
            e.preventDefault();
            if (selectedIndex.value >= 0 && results.value[selectedIndex.value]) {
                handleSelect(results.value[selectedIndex.value]);
            }
            break;
        case 'Escape':
            closeSearch();
            searchInput.value?.blur();
            break;
    }
};

// Close when clicking outside
const vClickOutside = {
    mounted(el, binding) {
        el.clickOutsideEvent = (event) => {
            if (!(el === event.target || el.contains(event.target))) {
                binding.value(event);
            }
        };
        document.body.addEventListener('click', el.clickOutsideEvent);
    },
    unmounted(el) {
        document.body.removeEventListener('click', el.clickOutsideEvent);
    }
};

const getIcon = (name) => icons[name] || Box;

</script>

<template>
    <div class="relative w-full max-w-md" v-click-outside="() => showResults = false">
        <div class="relative group">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <Search class="h-5 w-5 text-gray-400 group-focus-within:text-indigo-500 transition-colors" />
            </div>
            <input
                ref="searchInput"
                type="text"
                v-model="query"
                placeholder="Buscar activo, vehículo, código..."
                class="block w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg leading-5 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-500 focus:outline-none focus:bg-white dark:focus:bg-gray-800 focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 sm:text-sm transition-all"
                @focus="showResults = true"
                @keydown="onKeydown"
            />
            <div v-if="isLoading" class="absolute inset-y-0 right-0 pr-3 flex items-center">
                <Loader2 class="h-4 w-4 text-indigo-500 animate-spin" />
            </div>
        </div>

        <!-- Results Dropdown -->
        <div v-if="showResults && (results.length > 0 || (query.length >= 2 && !isLoading))" 
             class="absolute z-50 mt-2 w-full bg-white dark:bg-gray-800 rounded-xl shadow-xl ring-1 ring-black ring-opacity-5 overflow-hidden transform origin-top transition-all max-h-[80vh] overflow-y-auto custom-scrollbar">
            
            <div v-if="results.length > 0">
                <ul class="py-2">
                    <li v-for="(result, index) in results" :key="result.id">
                        <button
                            @click="handleSelect(result)"
                            @mouseenter="selectedIndex = index"
                            class="w-full text-left px-4 py-3 flex items-center gap-3 transition-colors"
                            :class="{ 
                                'bg-indigo-50 dark:bg-indigo-900/30': index === selectedIndex,
                                'hover:bg-gray-50 dark:hover:bg-gray-700/50': index !== selectedIndex 
                            }"
                        >
                            <div class="p-2 rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400 shrink-0">
                                <component :is="getIcon(result.icon)" class="w-5 h-5" />
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-bold text-gray-900 dark:text-white truncate">
                                    {{ result.title }}
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 truncate flex items-center gap-1">
                                    <span class="uppercase tracking-wider font-semibold text-[10px] bg-gray-200 dark:bg-gray-600 px-1.5 rounded-sm">{{ result.type }}</span>
                                    <span>{{ result.subtitle }}</span>
                                </p>
                            </div>
                            <ChevronRight class="w-4 h-4 text-gray-400" />
                        </button>
                    </li>
                </ul>
                <div class="px-4 py-2 bg-gray-50 dark:bg-gray-700/30 border-t border-gray-100 dark:border-gray-700 text-[10px] text-gray-400 uppercase font-bold tracking-wider text-center">
                    Presiona <span class="bg-gray-200 dark:bg-gray-600 px-1 rounded mx-1">Enter</span> para seleccionar
                </div>
            </div>

            <div v-else-if="query.length >= 2 && !isLoading" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                <Box class="w-12 h-12 mx-auto text-gray-300 dark:text-gray-600 mb-2" />
                <p>No se encontraron resultados para "{{ query }}"</p>
            </div>
        </div>
    </div>
</template>

<style scoped>
.custom-scrollbar::-webkit-scrollbar {
    width: 6px;
}
.custom-scrollbar::-webkit-scrollbar-track {
    background: transparent;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    background: rgba(156, 163, 175, 0.5);
    border-radius: 20px;
}
</style>

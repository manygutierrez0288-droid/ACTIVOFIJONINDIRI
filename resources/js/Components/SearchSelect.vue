<template>
    <div class="relative" ref="container">
        <!-- Input Display -->
        <div 
            @click="toggleDropdown"
            class="flex items-center justify-between w-full px-4 py-3 text-sm transition-all cursor-pointer rounded-xl border-gray-200 dark:bg-gray-900/50 dark:border-gray-700 dark:text-gray-100 hover:border-indigo-400 focus-within:ring-2 focus-within:ring-indigo-500/20 shadow-sm"
            :class="[
                disabled ? 'opacity-50 cursor-not-allowed bg-gray-50' : 'bg-white',
                error ? 'border-red-500 ring-2 ring-red-500/10' : 'border-gray-200 focus-within:border-indigo-500'
            ]"
        >
            <span class="truncate" :class="!selectedOption ? 'text-gray-400' : 'text-gray-900 dark:text-gray-100'">
                {{ selectedOption ? selectedOption.nombre : placeholder }}
            </span>
            <ChevronDown 
                class="w-4 h-4 text-gray-400 transition-transform duration-200"
                :class="{ 'rotate-180': isOpen }"
            />
        </div>

        <!-- Dropdown Menu -->
        <transition
            enter-active-class="transition duration-100 ease-out"
            enter-from-class="transform scale-95 opacity-0"
            enter-to-class="transform scale-100 opacity-100"
            leave-active-class="transition duration-75 ease-in"
            leave-from-class="transform scale-100 opacity-100"
            leave-to-class="transform scale-95 opacity-0"
        >
            <div 
                v-if="isOpen"
                class="absolute z-[60] mt-2 w-full bg-white dark:bg-gray-800 rounded-2xl shadow-2xl border border-gray-100 dark:border-gray-700 overflow-hidden"
            >
                <div class="p-2 border-b border-gray-50 dark:border-gray-700">
                    <div class="relative">
                        <Search class="absolute left-3 top-2.5 w-4 h-4 text-gray-400" />
                        <input 
                            ref="searchRef"
                            v-model="searchQuery"
                            type="text"
                            class="w-full pl-9 pr-4 py-2 text-sm bg-gray-50 dark:bg-gray-900 border-none rounded-xl focus:ring-0 text-gray-900 dark:text-white"
                            :placeholder="searchPlaceholder"
                            @keydown.esc="closeDropdown"
                        />
                    </div>
                </div>

                <div class="max-h-60 overflow-y-auto custom-scrollbar p-1">
                    <button
                        v-for="option in filteredOptions"
                        :key="option.id"
                        type="button"
                        @click="selectOption(option)"
                        class="w-full flex items-center gap-3 px-3 py-2.5 text-sm text-left rounded-xl transition-colors"
                        :class="[
                            modelValue == option.id 
                                ? 'bg-indigo-50 text-indigo-700 dark:bg-indigo-900/40 dark:text-indigo-300 font-bold' 
                                : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700/50'
                        ]"
                    >
                        <div 
                            v-if="modelValue == option.id" 
                            class="w-1.5 h-1.5 rounded-full bg-indigo-600"
                        ></div>
                        {{ option.nombre }}
                    </button>
                    
                    <div 
                        v-if="filteredOptions.length === 0" 
                        class="px-4 py-8 text-center text-xs text-gray-500 italic"
                    >
                        No se encontraron opciones.
                    </div>
                </div>
            </div>
        </transition>
    </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted, watch, nextTick } from 'vue';
import { ChevronDown, Search } from 'lucide-vue-next';

const props = defineProps({
    modelValue: [String, Number],
    options: {
        type: Array,
        default: () => []
    },
    placeholder: {
        type: String,
        default: 'Seleccionar...'
    },
    searchPlaceholder: {
        type: String,
        default: 'Buscar...'
    },
    error: String,
    disabled: {
        type: Boolean,
        default: false
    }
});

const emit = defineEmits(['update:modelValue']);

const isOpen = ref(false);
const searchQuery = ref('');
const container = ref(null);
const searchRef = ref(null);

const filteredOptions = computed(() => {
    if (!searchQuery.value) return props.options;
    const query = searchQuery.value.toLowerCase();
    return props.options.filter(opt => 
        opt.nombre.toLowerCase().includes(query)
    );
});

const selectedOption = computed(() => {
    return props.options.find(opt => opt.id == props.modelValue);
});

const toggleDropdown = () => {
    if (props.disabled) return;
    isOpen.value = !isOpen.value;
    if (isOpen.value) {
        searchQuery.value = '';
        nextTick(() => {
            searchRef.value?.focus();
        });
    }
};

const closeDropdown = () => {
    isOpen.value = false;
};

const selectOption = (option) => {
    emit('update:modelValue', option.id);
    closeDropdown();
};

const handleClickOutside = (event) => {
    if (container.value && !container.value.contains(event.target)) {
        closeDropdown();
    }
};

onMounted(() => {
    document.addEventListener('click', handleClickOutside);
});

onUnmounted(() => {
    document.removeEventListener('click', handleClickOutside);
});

watch(() => props.modelValue, () => {
    // console.log('Value changed:', props.modelValue);
});
</script>

<style scoped>
.custom-scrollbar::-webkit-scrollbar {
    width: 4px;
}
.custom-scrollbar::-webkit-scrollbar-track {
    background: transparent;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    background: #e2e8f0;
    border-radius: 10px;
}
.dark .custom-scrollbar::-webkit-scrollbar-thumb {
    background: #334155;
}
</style>

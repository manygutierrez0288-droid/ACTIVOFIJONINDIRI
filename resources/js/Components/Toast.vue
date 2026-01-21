<script setup>
import { ref, onMounted, watch } from 'vue';
import { CheckCircle, XCircle, X } from 'lucide-vue-next';

const props = defineProps({
    message: String,
    type: {
        type: String,
        default: 'success'
    },
    duration: {
        type: Number,
        default: 5000
    }
});

const emit = defineEmits(['close']);

const visible = ref(true);

const close = () => {
    visible.value = false;
    setTimeout(() => {
        emit('close');
    }, 300);
};

onMounted(() => {
    if (props.duration > 0) {
        setTimeout(close, props.duration);
    }
});
</script>

<template>
    <Transition
        enter-active-class="transform ease-out duration-300 transition"
        enter-from-class="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
        enter-to-class="translate-y-0 opacity-100 sm:translate-x-0"
        leave-active-class="transition ease-in duration-100"
        leave-from-class="opacity-100"
        leave-to-class="opacity-0"
    >
        <div v-if="visible" class="max-w-sm w-full bg-white dark:bg-gray-800 shadow-2xl rounded-2xl pointer-events-auto ring-1 ring-black ring-opacity-5 overflow-hidden border border-gray-100 dark:border-gray-700">
            <div class="p-4">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <CheckCircle v-if="type === 'success'" class="h-6 w-6 text-emerald-500" />
                        <XCircle v-else class="h-6 w-6 text-red-500" />
                    </div>
                    <div class="ml-3 w-0 flex-1 pt-0.5">
                        <p class="text-sm font-bold text-gray-900 dark:text-white">
                            {{ type === 'success' ? '¡Éxito!' : 'Error' }}
                        </p>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            {{ message }}
                        </p>
                    </div>
                    <div class="ml-4 flex-shrink-0 flex">
                        <button @click="close" class="bg-white dark:bg-gray-800 rounded-md inline-flex text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                            <span class="sr-only">Cerrar</span>
                            <X class="h-5 w-5" />
                        </button>
                    </div>
                </div>
            </div>
            <!-- Progress bar -->
            <div v-if="duration > 0" class="h-1 bg-gray-100 dark:bg-gray-700 w-full overflow-hidden">
                <div 
                    class="h-full bg-emerald-500 transition-all duration-linear"
                    :class="{ 'bg-red-500': type === 'error' }"
                    :style="{ animation: `shrink ${duration}ms linear forwards` }"
                ></div>
            </div>
        </div>
    </Transition>
</template>

<style scoped>
@keyframes shrink {
    from { width: 100%; }
    to { width: 0%; }
}
</style>

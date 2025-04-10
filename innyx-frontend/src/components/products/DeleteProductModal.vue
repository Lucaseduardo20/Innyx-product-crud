<template>
    <div v-if="open" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white dark:bg-quaternary-800 p-6 rounded-2xl shadow-lg w-full max-w-md">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">
                Tem certeza que deseja excluir este produto?
            </h2>

            <p class="text-sm text-gray-600 dark:text-quaternary-300 mb-6">
                Produto: <strong>{{ product.name }}</strong>
            </p>

            <div class="flex justify-end gap-3">
                <button @click="$emit('cancel')"
                    class="px-4 py-2 rounded-lg bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-white hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                    Cancelar
                </button>

                <button type="submit" @click="handleDelete"
                    class="px-4 py-2 w-24 flex items-center justify-center rounded-lg bg-red-600 text-white hover:bg-red-700 transition">
                    <Spinner :loading="loading" />
                    {{ loading ? '' : 'Confirmar' }}
                </button>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import type { Product } from '@/types/product'
import Spinner from '../shared/Spinner.vue';
import { ref } from 'vue';

const props = defineProps<{
    open: boolean
    product: Product
}>()
const loading = ref(false);
loading.value = false;

const handleDelete = () => {
    loading.value = true;
    emit('confirm', props.product.id)
}

const emit = defineEmits(['cancel', 'confirm'])
</script>
<style>
@keyframes spin {
    from {
        transform: rotate(0deg);
    }

    to {
        transform: rotate(360deg);
    }
}

.animate-spin {
    animation: spin 1s linear infinite;
}
</style>
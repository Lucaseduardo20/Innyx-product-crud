<template>
    <div
        class="max-w-sm w-full sm:w-80 bg-white dark:bg-quaternary-800 rounded-2xl shadow-md overflow-hidden border dark:border-quaternary-700 transition-all hover:shadow-lg">

        <div class="w-full aspect-video bg-gray-100 dark:bg-quaternary-700">
            <img v-if="product.image" :src="getImageUrl(product.image)" alt="Imagem do produto"
                class="w-full h-full object-cover" @error="onImageError" />
            <div v-else
                class="w-full h-full flex items-center justify-center text-gray-400 dark:text-quaternary-300 text-sm italic">
                Imagem indisponível
            </div>
        </div>

        <div class="p-5">
            <h2 class="font-bold text-lg text-gray-800 dark:text-white truncate">
                {{ product.name }}
            </h2>

            <p class="text-sm text-gray-600 dark:text-quaternary-300 mt-1 line-clamp-2">
                {{ product.description }}
            </p>

            <p class="text-base text-green-600 font-semibold mt-3">
                R$ {{ product.price.toFixed(2) }}
            </p>

            <div class="flex flex-wrap gap-2 mt-4">
                <button @click="$emit('edit', product)"
                    class="flex-1 py-2 px-3 rounded-lg bg-primary text-white text-sm font-medium hover:bg-[#a96ec6] focus:outline-none focus:ring-2 focus:ring-blue-300 transition">
                    ✏️ Editar
                </button>

                <button @click="$emit('delete', product)"
                    class="flex-1 py-2 px-3 rounded-lg bg-red-600 text-white text-sm font-medium hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-300 transition">
                    🗑️ Excluir
                </button>

                <button @click="$emit('view', product)"
                    class="w-full py-2 px-3 rounded-lg bg-gray-700 text-white text-sm font-medium hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-400 transition">
                    👁️ Ver mais
                </button>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import type { Product } from '@/types/product'
import { ref } from 'vue'

defineProps<{
    product: Product
}>()

defineEmits(['edit', 'delete', 'view'])

const hasError = ref(false)

const getImageUrl = (path: string) => {
    if (hasError.value) return ''
    return path.startsWith('http') ? path : `${import.meta.env.VITE_API_URL}/${path}`
}

const onImageError = () => {
    hasError.value = true
}
</script>

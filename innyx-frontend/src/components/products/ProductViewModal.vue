<template>
    <div v-if="open" class="fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center p-4">
        <div class="bg-white  text-gray-800 p-6 rounded-2xl shadow-xl w-full max-w-lg relative">
            <button @click="$emit('close')"
                class="absolute top-4 right-4 text-gray-400 hover:text-gray-700  transition">
                ✕
            </button>

            <div class="mb-4">
                <img v-if="product.image" :src="product.image ? getImageUrl(product.image) : placeholderImage"
                    alt="Imagem do produto" class="w-full h-64 object-cover rounded-xl border" />
                <div v-else class="w-full h-full flex items-center justify-center text-gray-400 text-sm italic">
                    Imagem indisponível
                </div>
            </div>

            <div class="space-y-2">
                <h2 class="text-2xl font-bold text-primary">
                    {{ product.name }}
                </h2>

                <p class="text-gray-600  leading-relaxed">
                    {{ product.description || 'Sem descrição disponível.' }}
                </p>
                <div class="flex items-center gap-2 mt-2">
                    <span class="text-green-600 font-semibold text-lg">
                        R$ {{ product.price.toFixed(2) }}
                    </span>
                </div>

                <div v-if="auth.user?.is_admin" class="flex items-center gap-2 mt-2">
                    <span class="text-primary font-semibold text-lg">
                        Produtor: {{ product.user_name }}
                    </span>
                </div>

                <div class="text-sm text-gray-500 mt-4 space-y-1">
                    <p v-if="product.valid_until">
                        <strong>Validade:</strong>
                        {{ formatDate(product.valid_until) }}
                    </p>
                    <p v-if="product.category_name">
                        <strong>Categoria:</strong> {{ product.category_name }}
                    </p>
                </div>
            </div>

            <div class="mt-6 flex justify-end">
                <button @click="$emit('close')"
                    class="px-5 py-2 rounded-lg bg-primary text-white hover:bg-primary/80 transition font-medium">
                    Fechar
                </button>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { useAuthStore } from '@/stores/auth';
import type { Product } from '@/types/product'

const props = defineProps<{
    open: boolean
    product: Product
}>()

const auth = useAuthStore();

defineEmits(['close'])

const placeholderImage =
    'https://via.placeholder.com/600x400?text=Sem+Imagem'

const getImageUrl = (path: string) => {
    return path.startsWith('http') ? path : `${import.meta.env.VITE_API_URL}/${path}`
}

const formatDate = (dateStr: string) => {
    const date = new Date(dateStr)
    return date.toLocaleDateString('pt-BR', {
        day: '2-digit',
        month: 'long',
        year: 'numeric',
    })
}
</script>

<style scoped></style>
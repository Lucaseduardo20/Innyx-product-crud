<template>
    <div class="p-4 bg-white rounded-xl shadow dark:bg-quaternary-800">
        <h2 class="font-semibold text-lg dark:text-white">{{ product.name }}</h2>
        <p class="text-sm text-quaternary-500 mt-1 dark:text-quaternary-300">
            {{ product.description }}
        </p>
        <p class="text-sm text-tertiary font-semibold mt-2">R$ {{ product.price.toFixed(2) }}</p>

        <div class="flex gap-2 mt-4">
            <button @click="$emit('edit', product)" class="text-blue-600 text-sm hover:underline">Editar</button>
            <button @click="$emit('delete', product.id)" class="text-red-600 text-sm hover:underline">Excluir</button>
            <button @click="$emit('view', product)" class="text-gray-600 text-sm hover:underline">Ver mais</button>
        </div>
    </div>
</template>

<script setup lang="ts">
import type { Product } from '@/types/product'

defineProps<{
    product: Product
}>()

defineEmits(['edit', 'delete', 'view'])
</script>
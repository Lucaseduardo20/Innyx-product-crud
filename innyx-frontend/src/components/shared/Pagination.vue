<template>
    <div class="flex justify-center items-center gap-2 mt-6">
        <button :disabled="page === 1" @click="$emit('update:page', page - 1)"
            class="px-3 py-1 rounded bg-gray-100 dark:bg-quaternary-700 disabled:opacity-50">
            ←
        </button>
        <span class="text-sm dark:text-white">{{ page }} / {{ totalPages }}</span>
        <button :disabled="page === totalPages" @click="$emit('update:page', page + 1)"
            class="px-3 py-1 rounded bg-gray-100 dark:bg-quaternary-700 disabled:opacity-50">
            →
        </button>
    </div>
</template>

<script setup lang="ts">
defineProps<{
    page: number
    totalPages: number
}>()

defineEmits(['update:page'])
</script>
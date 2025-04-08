<!-- src/components/CustomCard.vue -->
<template>
    <div class="p-4 rounded-xl shadow-md bg-white flex flex-col items-center text-center">
        <div class="text-4xl mb-2">{{ icon }}</div>
        <h2 class="text-sm font-semibold text-gray-500">{{ title }}</h2>
        <p class="text-xl font-bold text-primary">{{ value }}</p>
    </div>
</template>

<script setup lang="ts">
defineProps<{
    title: string
    value: string | number
    icon: string
}>()
</script>
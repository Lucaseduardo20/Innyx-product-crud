<script setup lang="ts">
import type { User } from '@/types/user'
const props = defineProps<{ user: User }>()
const emit = defineEmits(['edit', 'delete'])
</script>

<template>
    <div class="border rounded p-4 shadow-sm bg-white dark:bg-quaternary-800">
        <div class="font-semibold">{{ user.name }}</div>
        <div class="text-sm text-gray-600">{{ user.email }}</div>
        <div class="text-sm text-gray-500">Perfil: {{ user.role_name }}</div>

        <div class="flex gap-2 mt-4">
            <button @click="$emit('edit', user)" class="text-blue-500 hover:underline">Editar</button>
            <button @click="$emit('delete', user)" class="text-red-500 hover:underline">Excluir</button>
        </div>
    </div>
</template>

<script setup lang="ts">
import type { User } from '@/types/user'
import { ref } from 'vue';
import Spinner from '../shared/Spinner.vue';
const props = defineProps<{ user: User }>()
const emit = defineEmits(['edit', 'delete', 'reset'])
const loadingReset = ref<boolean>(false)


const handleResetPassword = () => {
    loadingReset.value = true;
    emit('reset', props.user.id)
    setTimeout(() => {
        loadingReset.value = false;
    }, 1000)
}
</script>

<template>
    <div class="border rounded-xl p-4 shadow-sm bg-purple-300">
        <div class="font-semibold">{{ user.name }}</div>
        <div class="text-sm text-gray-600">{{ user.email }}</div>
        <div class="text-sm text-gray-500">Perfil: {{ user.role_name }}</div>

        <div class="flex gap-2 mt-4">
            <button @click="$emit('edit', user)"
                class="text-white hover:bg-purple-600 w-24 bg-primary rounded-md">Editar</button>
            <button @click="$emit('delete', user)"
                class="bg-red-700 hover:bg-red-500 w-24 rounded-md text-white">Excluir</button>
            <button @click="handleResetPassword"
                class="flex justify-center items-center bg-blue-700 hover:bg-blue-500 w-24 rounded-md text-white">
                <Spinner :loading="loadingReset" />
                {{ loadingReset ? '' : 'Reset Pass' }}
            </button>
        </div>
    </div>
</template>

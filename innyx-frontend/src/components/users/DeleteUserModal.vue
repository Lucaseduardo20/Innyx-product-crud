<script setup lang="ts">
import type { User } from '@/types/user'

defineProps<{ open: boolean, user: User | null }>()
const emit = defineEmits(['confirm', 'cancel'])
</script>

<template>
    <div v-if="open" class="fixed inset-0 bg-black bg-opacity-40 flex justify-center items-center z-50">
        <div class="bg-white dark:bg-quaternary-900 p-6 rounded-lg w-full max-w-sm shadow">
            <p>Deseja realmente excluir o usuário <strong>{{ user?.name }}</strong>?</p>
            <div class="flex justify-end gap-2 mt-4">
                <button @click="$emit('cancel')" class="btn-secondary">Cancelar</button>
                <button @click="$emit('confirm', user?.id)" class="btn-danger">Excluir</button>
            </div>
        </div>
    </div>
</template>

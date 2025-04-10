<script setup lang="ts">
import type { User } from '@/types/user'
import Spinner from '../shared/Spinner.vue';
import { ref } from 'vue';

const props = defineProps<{ open: boolean, user: User | null }>()
const emit = defineEmits(['confirm', 'cancel'])
const loading = ref(false);

const handleDelete = () => {
    loading.value = true;
    emit('confirm', props.user?.id);
    setTimeout(() => {
        loading.value = false;
    }, 2000)
}
</script>

<template>
    <div v-if="open" class="fixed inset-0 bg-black bg-opacity-40 flex justify-center items-center z-50">
        <div class="bg-gray-200 p-6 rounded-lg w-full max-w-sm shadow">
            <p class="text-primary">Deseja realmente excluir o usuário <strong>{{ user?.name }}</strong>?</p>
            <div class="flex justify-end gap-2 mt-4">
                <button @click="$emit('cancel')"
                    class="bg-primary text-white hover:bg-purple-700 border border-gray-600 w-24 rounded-md">Cancelar</button>
                <button @click="handleDelete"
                    class="w-24 text-white bg-red-500 hover:bg-red-700 rounded-md flex justify-center items-center">
                    <Spinner :loading="loading" />
                    {{ loading ? '' : 'Excluir' }}
                </button>
            </div>
        </div>
    </div>
</template>

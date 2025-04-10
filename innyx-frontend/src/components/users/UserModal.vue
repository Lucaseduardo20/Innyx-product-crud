<script setup lang="ts">
import { ref, watch, onMounted } from 'vue'
import type { User } from '@/types/user'

const props = defineProps<{
    isOpen: boolean,
    initialData: User | null
}>()

const emit = defineEmits(['close', 'submit'])

const form = ref({} as User)

watch(() => props.initialData, () => {
    if (props.initialData) {
        form.value = { ...props.initialData }
    } else {
        form.value = { name: '', email: '', role_name: '' }
    }
}, { immediate: true })

const submit = () => {
    emit('submit', {
        payload: form.value,
        isEdit: !!props.initialData,
        id: props.initialData?.id
    })
}
</script>

<template>
    <div v-if="isOpen" class="fixed inset-0 bg-black bg-opacity-30 flex justify-center items-center z-50">
        <div class="bg-white dark:bg-quaternary-900 p-6 rounded-lg w-full max-w-md shadow">
            <h2 class="text-lg font-semibold mb-4">{{ initialData ? 'Editar Usuário' : 'Novo Usuário' }}</h2>

            <input v-model="form.name" type="text" placeholder="Nome" class="input mb-3" />
            <input v-model="form.email" type="email" placeholder="Email" class="input mb-3" />
            <select v-model="form.role_name" class="input mb-3">
                <option value="">Selecione o perfil</option>
                <option value="admin">Admin</option>
                <option value="colaborador">Colaborador</option>
            </select>

            <div class="flex justify-end gap-2 mt-4">
                <button @click="$emit('close')" class="btn-secondary">Cancelar</button>
                <button @click="submit" class="btn-primary">Salvar</button>
            </div>
        </div>
    </div>
</template>

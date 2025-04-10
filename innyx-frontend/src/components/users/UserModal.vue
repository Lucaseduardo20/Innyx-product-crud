<script setup lang="ts">
import { ref, watch, onMounted } from 'vue'
import type { User } from '@/types/user'
import Spinner from '../shared/Spinner.vue';

const props = defineProps<{
    isOpen: boolean,
    initialData: User | null
}>()

const emit = defineEmits(['close', 'submit'])

const form = ref({} as User)
const loading = ref<boolean>(false);

watch(() => props.initialData, () => {
    if (props.initialData) {
        form.value = { ...props.initialData }
    } else {
        form.value = { name: '', email: '', role_name: '', is_admin: false }
    }
}, { immediate: true })

const submit = () => {
    loading.value = true;
    emit('submit', {
        payload: form.value,
        isEdit: !!props.initialData,
        id: props.initialData?.id
    })
    setTimeout(() => {
        loading.value = false;
    }, 2000)
}
</script>

<template>
    <div v-if="isOpen" class="fixed inset-0 bg-primary/60 backdrop-blur-sm flex justify-center items-center z-50 p-4">
        <div
            class="bg-gradient-to-br from-white to-indigo-50 p-6 rounded-xl w-full max-w-md shadow-2xl border border-indigo-100">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold text-primary">
                    {{ initialData ? '✏️ Editar Usuário' : '👤 Novo Usuário' }}
                </h2>
                <button @click="$emit('close')" class="text-indigo-500 hover:text-indigo-700 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-indigo-700 mb-1">Nome</label>
                    <input v-model="form.name" type="text" placeholder="Digite o nome completo"
                        class="w-full px-4 py-2 rounded-lg border border-indigo-200 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all" />
                </div>

                <div>
                    <label class="block text-sm font-medium text-indigo-700 mb-1">Email</label>
                    <input v-model="form.email" type="email" placeholder="exemplo@email.com"
                        class="w-full px-4 py-2 rounded-lg border border-indigo-200 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all" />
                </div>

                <div>
                    <label class="block text-sm font-medium text-indigo-700 mb-1">Perfil</label>
                    <select v-model="form.role_name" placeholder="Selecione o Perfil"
                        class="w-full px-4 py-2 rounded-lg border border-indigo-200 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all appearance-none bg-white bg-[url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIyNCIgaGVpZ2h0PSIyNCIgdmlld0JveD0iMCAwIDI0IDI0IiBmaWxsPSJub25lIiBzdHJva2U9IiAjd2hpdGUiIHN0cm9rZS13aWR0aD0iMiIgc3Ryb2tlLWxpbmVjYXA9InJvdW5kIiBzdHJva2UtbGluZWpvaW49InJvdW5kIiBjbGFzcz0ibHVjaWRlIGx1Y2lkZS1jaGV2cm9uLWRvd24iPjxwYXRoIGQ9Im02IDkgNiA2IDYtNiIvPjwvc3ZnPg==')] bg-no-repeat bg-[right_0.5rem_center] bg-[length:1.5em]">
                        <option value="" class="text-primary" disabled selected>Selecione o perfil</option>
                        <option value="Admin" class="text-primary">👑 Administrador</option>
                        <option value="User" class="text-primary">👥 Colaborador</option>
                    </select>
                </div>

                <div class="flex justify-end gap-3 pt-4">
                    <button @click="$emit('close')"
                        class="px-5 py-2 rounded-lg border border-indigo-300 text-indigo-700 hover:bg-indigo-50 transition-colors font-medium">
                        Cancelar
                    </button>
                    <button @click="submit"
                        class="w-32 px-5 py-2 rounded-lg bg-gradient-to-r from-primary to-purple-500 text-white hover:from-purple-900 hover:to-purple-600 transition-all shadow-md hover:shadow-indigo-300 font-medium flex items-center justify-center gap-2">
                        <svg v-if="!loading" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                clip-rule="evenodd" />
                        </svg>
                        <Spinner :loading="loading" />
                        {{ loading ? '' : 'Salvar' }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
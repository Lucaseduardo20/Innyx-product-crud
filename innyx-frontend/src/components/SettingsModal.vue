<template>
    <div v-if="open" class="fixed inset-0 bg-primary bg-opacity-40 flex justify-center items-center z-50">
        <div class="bg-white -900 p-6 rounded-lg w-full max-w-sm shadow relative">
            <span @click="closeModal" class="absolute right-5 text-bold text-primary hover:text-purple-700 hover:cursor-pointer">X</span>
            <div v-if="!showChangePassword">
                <h2 class="text-lg font-semibold mb-4 text-primary ">Configurações</h2>
                <button @click="$emit('logout')"
                    class="w-full flex items-center space-x-3 p-3 rounded-lg text-white bg-purple-500  hover:bg-primary transition-all duration-200 mb-2">
                    <span class="text-xl">🚪</span>
                    <span class="font-medium">Sair</span>
                </button>
                <button @click="showChangePassword = true"
                    class="w-full flex items-center space-x-3 p-3 rounded-lg text-white bg-purple-500  hover:bg-primary transition-all duration-200">
                    <span class="text-xl">🔒</span>
                    <span class="font-medium">Alterar Senha</span>
                </button>
            </div>

            <div v-else>
                <h2 class="text-lg font-semibold mb-4 text-primary ">Alterar Senha</h2>
                <input v-model="currentPassword" type="password" placeholder="Senha atual"
                    class="w-full p-2 border rounded outline-none mb-3 focus:outline-primary" />
                <input v-model="newPassword" type="password" placeholder="Nova senha"
                    class="w-full p-2 border rounded outline-none mb-3 focus:outline-primary" />
                <div class="flex justify-end gap-2 mt-4">
                    <button @click="showChangePassword = false"
                        class="w-20 border-b rounded-md h-8 hover:bg-gray-100 transition-all">Voltar</button>
                    <button @click="changePassword"
                        class="w-20 bg-primary rounded-md text-white  hover:bg-purple-700 transition-all flex justify-center items-center">
                        <Spinner :loading="loading" />
                        {{ loading ? '' : 'Salvar' }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
<script setup lang="ts">
import { useUserStore } from '@/stores/user';
import type { ChangePasswordType } from '@/types/user';
import { ref } from 'vue'
import { toast } from 'vue3-toastify';
import Spinner from './shared/Spinner.vue';
import { useAuthStore } from '@/stores/auth';
import { useRouter } from 'vue-router';

const emit = defineEmits(['close', 'logout'])
const user = useUserStore();
const props = defineProps<{
    open: boolean
}>()

const loading = ref<boolean>(false)
const auth = useAuthStore();
const router = useRouter();

const changePassword = async () => {
    loading.value = true;
    const data: ChangePasswordType =
    {
        currentPassword: currentPassword.value,
        newPassword: newPassword.value
    }

    const response = await user.changePassword(data)
    const message = response.data;
    const errorStatus: any = {
        404: () => toast.error(message),
        400: () => toast.error(message),
        500: () => toast.error(message),
        200: () => toast.success(message)
    }

    showChangePassword.value = false;
    errorStatus[response.status]();
    if (response.status === 200) {

        setTimeout(() => {
            auth.logout()
            router.push('/')
        }, 2000)
    }
    return;
}

const closeModal = () => {
    emit('close');
    showChangePassword.value = false;
    currentPassword.value = '';
    newPassword.value = '';
}


const showChangePassword = ref(false)
const currentPassword = ref<string>('')
const newPassword = ref<string>('')

</script>
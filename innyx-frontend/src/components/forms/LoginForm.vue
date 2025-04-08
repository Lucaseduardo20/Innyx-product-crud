<template>
    <form @submit.prevent="handleLogin"
        class="flex flex-col gap-4 w-full max-w-md mx-auto mt-20 p-6 rounded-2xl shadow-md bg-white">

        <Logo />

        <h1 class="text-2xl font-bold text-primary text-center">Entrar na plataforma</h1>

        <div class="space-y-1">
            <input v-model="email" type="email" placeholder="Email" class="input"
                :class="{ 'border-red-500': errors.email }" @blur="validateEmail" required />
            <p v-if="errors.email" class="text-red-500 text-sm">{{ errors.email }}</p>
        </div>

        <div class="space-y-1">
            <input v-model="password" type="password" placeholder="Senha" class="input"
                :class="{ 'border-red-500': errors.password }" @blur="validatePassword" required />
            <p v-if="errors.password" class="text-red-500 text-sm">{{ errors.password }}</p>
        </div>

        <button type="submit" :disabled="loading" class="btn-primary">
            <span v-if="loading" class="animate-spin mr-2">🔄</span>
            Entrar
        </button>
    </form>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { useAuthStore } from '@/stores/auth'
import { useRouter } from 'vue-router'
import Logo from '@/components/branding/Logo.vue'
import { loginService } from '@/services/auth'
import { toast } from 'vue3-toastify'
import type { ToastOptions } from 'vue3-toastify'

const email = ref('')
const password = ref('')
const loading = ref(false)
const errors = ref({
    email: '',
    password: ''
})

const router = useRouter()
const auth = useAuthStore()

const toastOptions: ToastOptions = {
    position: toast.POSITION.TOP_RIGHT,
    autoClose: 5000,
    closeButton: true,
    theme: 'colored'
}

const validateEmail = () => {
    if (!email.value) {
        errors.value.email = 'Email é obrigatório'
        return false
    }

    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
    if (!emailRegex.test(email.value)) {
        errors.value.email = 'Por favor, insira um email válido'
        return false
    }

    errors.value.email = ''
    return true
}

const validatePassword = () => {
    if (!password.value) {
        errors.value.password = 'Senha é obrigatória'
        return false
    }

    if (password.value.length < 6) {
        errors.value.password = 'Senha deve ter pelo menos 6 caracteres'
        return false
    }

    errors.value.password = ''
    return true
}

const validateForm = () => {
    const isEmailValid = validateEmail()
    const isPasswordValid = validatePassword()
    return isEmailValid && isPasswordValid
}

const handleLogin = async () => {
    if (!validateForm()) return

    try {
        loading.value = true

        const response = await loginService({
            email: email.value,
            password: password.value
        })

        if (!response.ok) {
            const data = await response.json()
            throw new Error(data.message || 'Erro no login')
        }

        const data = await response.json()
        auth.setAuth(data.token, data.user)
        localStorage.setItem('token', data.token)

        toast.success('Login realizado com sucesso!', toastOptions)
        router.push('/dashboard')
    } catch (error: any) {
        toast.error(error.message || 'Erro ao fazer login', toastOptions)
        console.error('Login error:', error)
    } finally {
        loading.value = false
    }
}
</script>

<style scoped>
.input {
    @apply w-full border border-gray-300 rounded-lg p-3 outline-none focus:ring-2 focus:ring-primary transition;
}

.btn-primary {
    @apply bg-primary text-white rounded-lg p-3 font-bold hover:bg-opacity-90 transition flex justify-center items-center;
}

.btn-primary:disabled {
    @apply bg-opacity-70 cursor-not-allowed;
}
</style>
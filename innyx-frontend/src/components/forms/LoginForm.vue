<template>
    <form @submit.prevent="handleLogin"
        class="flex flex-col gap-4 w-full max-w-md mx-auto mt-20 p-6 rounded-2xl shadow-md bg-white">

        <Logo />

        <h1 class="text-2xl font-bold text-primary text-center">Entrar na plataforma</h1>

        <input v-model="email" type="email" placeholder="Email" class="input" required />
        <input v-model="password" type="password" placeholder="Senha" class="input" required />

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

const email = ref('')
const password = ref('')
const loading = ref(false)

const router = useRouter()
const auth = useAuthStore()

const handleLogin = async () => {
    try {
        loading.value = true

        const response = await loginService({ email: email.value, password: password.value })

        const data = await response.json()

        if (!response.ok) throw new Error(data.message || 'Erro no login')

        auth.setAuth(data.token, data.user)
        localStorage.setItem('token', data.token);
        router.push('/dashboard')
    } catch (error: any) {
        alert(error.message)
    } finally {
        loading.value = false
    }
}
</script>

<style scoped>
.input {
    @apply border border-gray-300 rounded-lg p-3 outline-none focus:ring-2 focus:ring-primary transition;
}

.btn-primary {
    @apply bg-primary text-white rounded-lg p-3 font-bold hover:bg-opacity-90 transition flex justify-center items-center;
}
</style>
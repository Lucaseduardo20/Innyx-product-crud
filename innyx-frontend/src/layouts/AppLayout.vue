<template>
    <div class="min-h-screen bg-senary-50">
        <SettingsModal :open="showSettingsModal" @logout="logout" @close="closeSettingsModal" />

        <aside class="hidden sm:flex fixed left-0 top-0 h-full w-64 bg-[#1f1231] shadow-lg flex-col z-20">
            <div class="p-6 flex justify-center">
                <Logo class="h-10" />
            </div>
            <nav class="flex-1 px-4 py-6 space-y-2">
                <NavLink to="/dashboard" icon="🏠" label="Dashboard" />
                <NavLink to="/products" icon="📦" label="Produtos" />
                <NavLink v-if="auth.user?.is_admin" to="/users" icon="👤" label="Usuários" />
            </nav>
            <div class="p-4 border-t dark:border-quaternary-700">
                <button @click="showSettingsModal = true"
                    class="w-full flex items-center space-x-3 p-3 rounded-lg text-tertiary-600 dark:text-tertiary hover:bg-senary transition-all duration-200">
                    <span class="text-xl">⚙️</span>
                    <span class="font-medium">Configurações</span>
                </button>
            </div>
        </aside>

        <div class="sm:ml-64">
            <header class="bg-secondary dark:bg-quaternary shadow-md p-4 sticky top-0 z-10">
                <div class="flex justify-between items-center max-w-5xl mx-auto">
                    <h1 class="text-xl font-bold text-primary dark:text-quinary">
                        Olá, {{ auth.user?.name || 'Usuário' }} 👋
                    </h1>
                    <div class="sm:hidden">
                        <button @click="showSettingsModal = true"
                            class="text-tertiary-600 dark:text-tertiary font-semibold text-sm">
                            ⚙️
                        </button>
                    </div>
                </div>
            </header>

            <main class="p-4 max-w-5xl mx-auto">
                <div class="w-full flex justify-center sm:hidden">
                    <SLogo />
                </div>
                <slot />
            </main>
        </div>

        <nav class="fixed bottom-0 left-0 right-0 bg-[#1f1231] shadow-inner border-t sm:hidden">
            <div class="flex justify-around items-center p-2">
                <NavLink to="/dashboard" icon="🏠" label="Dashboard" mobile />
                <NavLink to="/products" icon="📦" label="Produtos" mobile />
                <NavLink v-if="auth.user?.is_admin" to="/users" icon="👤" label="Usuários" mobile />
            </div>
        </nav>
    </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { useAuthStore } from '@/stores/auth'
import { useRouter } from 'vue-router'
import Logo from '@/components/branding/Logo.vue'
import SLogo from '@/components/branding/SLogo.vue'
import NavLink from '@/components/NavLink.vue'
import { useUserStore } from '@/stores/user'
import SettingsModal from '@/components/SettingsModal.vue'

const auth = useAuthStore()
const user = useUserStore();
const router = useRouter()

const showSettingsModal = ref(false)

const logout = () => {
    auth.logout()
    router.push('/')
    showSettingsModal.value = false
}

const closeSettingsModal = async () => {
    showSettingsModal.value = false;
}
</script>
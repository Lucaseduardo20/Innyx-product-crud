<script setup lang="ts">
import { ref, onMounted, watch } from 'vue'
import AppLayout from '@/layouts/AppLayout.vue'
import UserCard from '@/components/users/UserCard.vue'
import UserModal from '@/components/users/UserModal.vue'
import DeleteUserModal from '@/components/users/DeleteUserModal.vue'
import Pagination from '@/components/shared/Pagination.vue'
import { useUserStore } from '@/stores/user'
import type { User } from '@/types/user'
import { toast } from 'vue3-toastify'

const store = useUserStore()
const isModalOpen = ref(false)
const selectedUser = ref<User | null>(null)
const deletingUser = ref<User | null>(null)
const deleteModalTrigger = ref(false)
const currentPage = ref(1)
const search = ref('')

const openModal = (user: User | null = null) => {
    selectedUser.value = user
    isModalOpen.value = true
}

const closeModal = () => {
    selectedUser.value = null
    isModalOpen.value = false
}

const handleSave = async ({ payload, isEdit, id }: any) => {
    if (isEdit && id) {
        await store.updateUser(id, payload).then(() => {
            toast.success('Usuário atualizado com sucesso!')
        }).catch(() => {
            toast.error('Erro ao atualizar usuário')
        })
    } else {
        await store.addUser(payload).then(() => {
            toast.success('Usuário incluído com sucesso!')
        }).catch(() => {
            toast.error('Erro ao adicionar usuário')
        })
    }
    closeModal()
}

const openDeleteModal = (user: User) => {
    deletingUser.value = user
    deleteModalTrigger.value = true
}

const confirmDelete = async (id: number) => {
    await store.removeUser(id).then(() => {
        toast.info('Usuário excluído com sucesso!')
        deleteModalTrigger.value = false
    }).catch(() => {
        toast.error('Erro ao excluir usuário')
    })
}

const cancelDelete = () => {
    deletingUser.value = null
    deleteModalTrigger.value = false
}

onMounted(() => {
    store.fetchUsers({ page: currentPage.value, search: search.value })
})

watch([currentPage, search], () => {
    store.fetchUsers({ page: currentPage.value, search: search.value })
})
</script>

<template>
    <AppLayout>
        <div class="px-4 py-6 mb-12 max-w-3xl mx-auto">
            <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                <h1 class="text-xl font-bold dark:text-white">Usuários</h1>
                <button @click="openModal()"
                    class="w-40 bg-primary text-white hover:bg-purple-500 rounded-xl h-8 transition-all">+
                    Adicionar
                    Usuário</button>
            </div>

            <div class="mt-4">
                <input v-model="search" @blur="store.fetchUsers({ page: 1, search })" type="text"
                    placeholder="Buscar usuários..." class="w-full px-3 py-2 border rounded dark:bg-quaternary-800" />
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-6">
                <UserCard v-for="user in store.users" :key="user.id" :user="user" @edit="openModal"
                    @delete="openDeleteModal" />
            </div>

            <Pagination v-if="store.pagination.total > 10" :page="store.pagination.current_page"
                :totalPages="store.pagination.last_page" @update:page="(val) => currentPage = val" />
        </div>

        <UserModal :isOpen="isModalOpen" :initialData="selectedUser" @close="closeModal" @submit="handleSave" />
        <DeleteUserModal :open="deleteModalTrigger" :user="deletingUser" @cancel="cancelDelete"
            @confirm="confirmDelete" />
    </AppLayout>
</template>

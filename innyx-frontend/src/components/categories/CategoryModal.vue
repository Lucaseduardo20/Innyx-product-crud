<template>
    <div v-if="isOpen" class="fixed inset-0 z-50 bg-primary bg-opacity-50 flex items-center justify-center p-4">
        <div class="bg-white w-full max-w-md rounded-2xl p-4 shadow-lg relative">
            <button @click="emit('close')" class="absolute top-2 right-2 text-gray-400 hover:text-red-500 text-xl">
                &times;
            </button>

            <h2 class="text-xl font-semibold mb-4 text-zinc-800">Gerenciar Categorias</h2>

            <form @submit.prevent="handleCreateCategory" class="flex gap-2 mb-4">
                <input v-model="newCategoryName" type="text" placeholder="Nome da categoria"
                    class="flex-1 rounded-lg text-purple-900 border border-primary px-3 py-2 text-sm focus:outline-none focus:ring-2 ring-primary" />
                <button type="submit"
                    class="bg-primary w-20 flex justify-center items-center text-white px-4 py-2 rounded-lg hover:cursor-pointer hover:bg-purple-700 text-sm"
                    :disabled="loading || !newCategoryName.trim()">
                    <Spinner :loading="loading" class="w-4 h-4" />
                    <span>{{ loading ? '' : 'Adicionar' }}</span>
                </button>
            </form>

            <div class="max-h-64 overflow-y-auto pr-1 space-y-2 scroll-smooth">
                <div v-for="category in categories" :key="category.id"
                    class="flex justify-between items-center bg-zinc-100 dark:bg-zinc-800 rounded-lg px-3 py-2">
                    <span class="text-zinc-800 dark:text-white text-sm truncate">{{ category.name }}</span>
                    <button @click="handleDeleteCategory(category.id)"
                        class="text-red-500 text-sm hover:underline disabled:opacity-50" :disabled="loading">
                        Deletar
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, onMounted, computed } from 'vue'
import { useCategoryStore } from '@/stores/category'
import Spinner from '../shared/Spinner.vue'
import { toast } from 'vue3-toastify';

const emit = defineEmits(['close'])

const props = defineProps<{
    isOpen: boolean
}>()

const loading = ref(false)
const newCategoryName = ref('')

const store = useCategoryStore()
const categories = computed(() => store.categories)

const loadCategories = async () => {
    loading.value = true
    await store.fetchCategories()
    loading.value = false
}

const handleCreateCategory = async () => {
    if (!newCategoryName.value.trim()) return

    loading.value = true
    const response = await store.storeCategory(newCategoryName.value)
    if (response.status !== 200) {
        newCategoryName.value = ''
        loading.value = false
        return toast.error('Erro ao incluir nova categoria.')
    }
    await loadCategories()
    newCategoryName.value = ''
    loading.value = false
    toast.success('Categoria adicionada com sucesso!');
}

const handleDeleteCategory = async (id: number) => {
    loading.value = true
    await store.removeCategory(id)
    await loadCategories()
    loading.value = false
}

onMounted(() => {
    loadCategories()
})
</script>

<style scoped>
/* Estilo para scroll suave */
::-webkit-scrollbar {
    width: 6px;
}

::-webkit-scrollbar-thumb {
    background-color: rgba(0, 0, 0, 0.1);
    border-radius: 3px;
}
</style>
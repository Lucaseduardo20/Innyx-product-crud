<script setup lang="ts">
import { ref, onMounted, watch } from 'vue'
import AppLayout from '@/layouts/AppLayout.vue'
import ProductCard from '@/components/products/ProductCard.vue'
import ProductModal from '@/components/products/ProductModal.vue'
import Pagination from '@/components/shared/Pagination.vue'
import { useProductStore } from '@/stores/product'
import type { Product } from '@/types/product'

const store = useProductStore()

const isModalOpen = ref(false)
const selectedProduct = ref<Product | null>(null)
const currentPage = ref(1)
const search = ref('')

const openModal = (product = null) => {
    selectedProduct.value = product
    isModalOpen.value = true
}

const closeModal = () => {
    selectedProduct.value = null
    isModalOpen.value = false
}

const handleSave = async (data: any) => {
    if (selectedProduct.value) {
        await store.updateProduct(selectedProduct.value.id, data)
    } else {
        await store.addProduct(data)
    }
    closeModal()
}

const handleDelete = async (id: number) => {
    if (confirm('Tem certeza que deseja remover este produto?')) {
        await store.removeProduct(id)
    }
}

onMounted(() => {
    store.fetchProducts({ page: currentPage.value, search: search.value })
})

watch([currentPage, search], () => {
    store.fetchProducts({ page: currentPage.value, search: search.value })
})

const handleView = (product: Product) => {
    window.alert(`Ver mais: ${product.name}`)
}
</script>

<template>
    <AppLayout>
        <div class="px-4 py-6 max-w-3xl mx-auto">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <h1 class="text-xl font-bold dark:text-white">Produtos</h1>
                <button @click="openModal()"
                    class="bg-primary text-white px-4 py-2 rounded hover:bg-primary-dark transition">
                    + Adicionar Produto
                </button>
            </div>

            <div class="mt-4">
                <input v-model="search" type="text" placeholder="Buscar produtos..."
                    class="w-full px-3 py-2 border rounded dark:bg-quaternary-800 dark:text-white" />
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-6">
                <ProductCard v-for="product in store.products" :key="product.id" :product="product" @edit="openModal"
                    @delete="handleDelete" @view="handleView" />
            </div>

            <Pagination v-if="store.pagination.total > 10" :page="store.pagination.current_page"
                :totalPages="store.pagination.last_page" @update:page="(val) => currentPage = val" />
        </div>

        <ProductModal :categories='[{ id: 1, name: "InfoProduto" }]' :isOpen="isModalOpen"
            :initialData="selectedProduct" @close="closeModal" @submit="handleSave" />
    </AppLayout>
</template>

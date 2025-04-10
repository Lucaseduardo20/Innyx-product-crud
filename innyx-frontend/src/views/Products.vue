<script setup lang="ts">
import { ref, onMounted, watch } from 'vue'
import AppLayout from '@/layouts/AppLayout.vue'
import ProductCard from '@/components/products/ProductCard.vue'
import ProductModal from '@/components/products/ProductModal.vue'
import Pagination from '@/components/shared/Pagination.vue'
import { useProductStore } from '@/stores/product'
import type { Product } from '@/types/product'
import { toast } from 'vue3-toastify'
import DeleteProductModal from '@/components/products/DeleteProductModal.vue'
import { deleteProduct } from '@/services/product'

const store = useProductStore()

const isModalOpen = ref(false)
const selectedProduct = ref<Product | null>(null)
const currentPage = ref(1)
const search = ref('')
const deletingProduct = ref<Product | null>(null);
const deleteModalTrigger = ref<boolean>(false);

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
        closeModal()
        toast.success('Produto incluído com sucesso!')
        await store.addProduct(data);
    }
}

const openDeletingModal = async (product: Product) => {
    deleteModalTrigger.value = true;
    deletingProduct.value = product;
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

const confirmDelete = async (id: number) => {
    await store.removeProduct(id).then((res) => {
        deleteModalTrigger.value = false;
        toast.info('Produto exluído com sucesso!')
    }).catch((error) => {
        toast.error(error.message ?? 'Erro ao excluir o produto')
    });
}

const cancelDelete = () => {
    deleteModalTrigger.value = false;
    deletingProduct.value = null;
}
</script>

<template>
    <AppLayout>
        <div class="px-4 py-6 mb-12 max-w-3xl mx-auto">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <h1 class="text-xl font-bold dark:text-white">Produtos</h1>
                <button @click="openModal()"
                    class="bg-primary text-white px-4 py-2 rounded hover:bg-primary-dark transition btn-primary">
                    + Adicionar Produto
                </button>
            </div>

            <div class="mt-4">
                <input v-model="search" type="text" placeholder="Buscar produtos..."
                    class="w-full px-3 py-2 border rounded dark:bg-quaternary-800" />
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-6">
                <ProductCard v-for="product in store.products" :key="product.id" :product="product" @edit="openModal"
                    @delete="openDeletingModal" @view="handleView" />
            </div>

            <Pagination v-if="store.pagination.total > 10" :page="store.pagination.current_page"
                :totalPages="store.pagination.last_page" @update:page="(val) => currentPage = val" />
        </div>

        <ProductModal :categories='[{ id: 1, name: "InfoProduto" }]' :isOpen="isModalOpen"
            :initialData="selectedProduct" @close="closeModal" @submit="handleSave" />

        <DeleteProductModal @cancel="cancelDelete" @confirm="confirmDelete" :open="deleteModalTrigger"
            :product="deletingProduct as Product" />
    </AppLayout>
</template>

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
import ProductViewModal from '@/components/products/ProductViewModal.vue'
import { useAuthStore } from '@/stores/auth'
import CategoryModal from '@/components/categories/CategoryModal.vue'

const store = useProductStore()
const auth = useAuthStore();

const isModalOpen = ref(false)
const selectedProduct = ref<Product | null>(null)
const currentPage = ref(1)
const search = ref('')
const deletingProduct = ref<Product | null>(null);
const deleteModalTrigger = ref<boolean>(false);
const viewingProduct = ref<Product | null>(null);
const viewingModalTrigger = ref<boolean>(false);
const categoryModal = ref<boolean>(false);

const openModal = (product = null) => {
    selectedProduct.value = product
    isModalOpen.value = true
}

const closeModal = () => {
    selectedProduct.value = null
    isModalOpen.value = false
}

const handleSave = async ({ payload, isEdit, id }: any) => {
    if (isEdit && id) {
        return await store.updateProduct(id, payload).then(() => {
            toast.success('Produto atualizado com sucesso!')
            closeModal()
        }).catch(() => {
            toast.error('Erro ao atualizar o produto!')
            closeModal()
        })
    } else {
        await store.addProduct(payload).then(() => {
            toast.success('Produto incluído com sucesso!')
            closeModal()
        }).catch(() => {
            toast.error('Erro ao adicionar o produto!')
            closeModal()
        })
    }
}

const handleSearchBlur = () => {
    store.fetchProducts({ page: currentPage.value, search: search.value })
}

const openDeletingModal = async (product: Product) => {
    deleteModalTrigger.value = true;
    deletingProduct.value = product;
}

onMounted(() => {
    store.fetchProducts({ page: currentPage.value, search: search.value })
})

watch(currentPage, () => {
    store.fetchProducts({ page: currentPage.value, search: search.value })
})

const handleView = (product: Product) => {
    viewingProduct.value = product;
    viewingModalTrigger.value = true;
}

const closeViewModal = () => {
    viewingProduct.value = null;
    viewingModalTrigger.value = false;
}

const confirmDelete = async (id: number) => {
    await store.removeProduct(id).then(() => {
        deleteModalTrigger.value = false;
        toast.info('Produto excluído com sucesso!')
    }).catch((error) => {
        toast.error(error.message ?? 'Erro ao excluir o produto')
    });
}

const cancelDelete = () => {
    deleteModalTrigger.value = false;
    deletingProduct.value = null;
}

const addCategoryModal = () => {
    categoryModal.value = true;
}

const categoriesClose = () => {
    categoryModal.value = false;
}
</script>

<template>
    <AppLayout>
        <div class="px-4 py-6 mb-12 max-w-3xl mx-auto">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <button v-if="!auth.user?.is_admin" @click="openModal()"
                    class="bg-primary text-white px-4 py-2 rounded hover:bg-primary-dark transition btn-primary">
                    + Adicionar Produto
                </button>

                <button v-else @click="addCategoryModal()"
                    class="bg-primary text-white px-4 py-2 rounded hover:bg-primary-dark transition btn-primary">
                    Categorias
                </button>
            </div>

            <div class="mt-4">
                <input v-model="search" type="text" placeholder="Buscar produtos..."
                    class="w-full px-3 py-2 border rounded dark:bg-quaternary-800" @blur="handleSearchBlur" />
            </div>


            <div v-if="store.products.length > 0" class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-6">
                <ProductCard v-for="product in store.products" :key="product.id" :product="product" @edit="openModal"
                    @delete="openDeletingModal" @view="handleView" />
            </div>

            <div v-else
                class="py-10 w-full h-full flex items-center justify-center text-gray-400 dark:text-quaternary-300 text-sm italic">
                Não há produtos cadastrados.
            </div>

            <Pagination v-if="store.pagination.total > 10" :page="store.pagination.current_page"
                :totalPages="store.pagination.last_page" @update:page="(val) => currentPage = val" />
        </div>

        <ProductModal :categories='[{ id: 1, name: "InfoProduto" }]' :isOpen="isModalOpen"
            :initialData="selectedProduct" @close="closeModal" @submit="handleSave" />

        <DeleteProductModal @cancel="cancelDelete" @confirm="confirmDelete" :open="deleteModalTrigger"
            :product="deletingProduct as Product" />

        <ProductViewModal @close="closeViewModal" :open="viewingModalTrigger" :product="viewingProduct as Product" />
        <CategoryModal @close="categoriesClose" :is-open="categoryModal" />
    </AppLayout>
</template>

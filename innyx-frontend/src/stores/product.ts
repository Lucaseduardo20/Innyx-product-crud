import { defineStore } from 'pinia'
import { ref } from 'vue'
import type { Product, ProductResponseList } from '@/types/product'
import type { Category } from '@/types/category'
import {
  getProducts,
  getProductById,
  createProduct,
  updateProduct,
  deleteProduct,
} from '@/services/product'
import { getCategories } from '@/services/category'

export const useProductStore = defineStore('product', () => {
  const products = ref<Product[]>([])
  const categories = ref<Category[]>([])
  const loading = ref(false)
  const selectedProduct = ref<Product | null>(null)
  const pagination = ref({
    current_page: 1,
    last_page: 1,
    total: 0,
  })

  async function fetchProducts(queryParams: Record<string, any> = {}) {
    loading.value = true
    try {
      const response = await getProducts(queryParams)
      const data: ProductResponseList = response.data
      products.value = data.products
      // Atualiza a paginação com os metadados do response
      pagination.value = {
        current_page: data.current_page,
        last_page: data.last_page,
        total: data.total,
      }
    } finally {
      loading.value = false
    }
  }

  async function fetchCategories() {
    const response = await getCategories()
    categories.value = response.data
  }

  async function fetchProductById(id: number) {
    const response = await getProductById(id)
    selectedProduct.value = response.data
    return response.data
  }

  async function addProduct(payload: FormData) {
    const response = await createProduct(payload)
    await fetchProducts()
    return response.data
  }

  async function updateProduct(id: number, payload: FormData) {
    const response: any = await updateProduct(id, payload)
    await fetchProducts()
    return response.data
  }

  async function removeProduct(id: number) {
    await deleteProduct(id)
    await fetchProducts()
  }

  return {
    products,
    categories,
    pagination,
    loading,
    selectedProduct,
    fetchProducts,
    fetchCategories,
    fetchProductById,
    addProduct,
    updateProduct,
    removeProduct,
  }
})

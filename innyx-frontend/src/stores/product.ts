import { defineStore } from 'pinia'
import { ref } from 'vue'
import type { Product } from '@/types/product'
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

  async function fetchProducts(queryParams: Record<string, any> = {}) {
    loading.value = true
    try {
      const response = await getProducts(queryParams)
      products.value = response.data
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

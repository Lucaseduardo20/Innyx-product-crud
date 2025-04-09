import type { Product, ProductResponseList } from '@/types/product'
import { api } from './api'

export function getProducts(params = {}) {
    return api.get<ProductResponseList>('/products', { params })
}

export function getProductById(id: number) {
    return api.get<Product>(`/products/${id}`)
}

export function createProduct(data: FormData) {
    return api.post('/products', data, {
        headers: {
            'Content-Type': 'multipart/form-data',
        },
    })
}

export function updateProduct(id: number, data: FormData) {
    return api.post(`/products/${id}?_method=PUT`, data, {
        headers: {
            'Content-Type': 'multipart/form-data',
        },
    })
}

export function deleteProduct(id: number) {
    return api.delete(`/products/${id}`)
}

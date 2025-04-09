import { defineStore } from 'pinia'
import type { Product, ProductFilters, PaginatedResponse } from '@/types/product'
import axios from 'axios'

export const useProductStore = defineStore('product', {
  state: () => ({
    products: [] as Product[],
    pagination: {
      current_page: 1,
      last_page: 1,
      total: 0
    },
    isLoading: false,
  }),
  actions: {
    async fetchProducts(filters: ProductFilters = {}) {
      this.isLoading = true;
      try {
        const { data } = await axios.get<PaginatedResponse<Product>>('/api/products', {
          params: filters
        });
        this.products = data.data;
        this.pagination = {
          current_page: data.current_page,
          last_page: data.last_page,
          total: data.total
        }
      } catch (err) {
        console.error('Erro ao buscar produtos:', err);
      } finally {
        this.isLoading = false;
      }
    },

    async addProduct(payload: Partial<Product>) {
      const { data } = await axios.post<Product>('/api/products', payload);
      this.fetchProducts(); // refresh
      return data;
    },

    async updateProduct(id: number, payload: Partial<Product>) {
      const { data } = await axios.put<Product>(`/api/products/${id}`, payload);
      this.fetchProducts(); // refresh
      return data;
    },

    async deleteProduct(id: number) {
      await axios.delete(`/api/products/${id}`);
      this.fetchProducts(); // refresh
    }
  }
});

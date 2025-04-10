import { defineStore } from 'pinia'
import { getUsers, createUser, updateUser, deleteUser } from '@/services/user'
import type { Category } from '@/types/category'
import type { User } from '@/types/user'
import { deleteCategory, getCategories, setCategories } from '@/services/category'

export const useCategoryStore = defineStore('category', {
    state: () => ({
        categories: null as Category[] | null
    }),

    actions: {
        async fetchCategories() {
            return await getCategories();
        },
        async storeCategory(name: string) {
            return await setCategories(name);
        },
        async removeCategory(id: number) {
            return await deleteCategory(id);
        }

    }
})

import { defineStore } from 'pinia'
import { getUsers, createUser, updateUser, deleteUser, resetPasswordService, changePasswordService } from '@/services/user'
import type { User, ChangePasswordType } from '@/types/user'

export const useUserStore = defineStore('user', {
  state: () => ({
    users: [] as User[],
    pagination: {
      current_page: 1,
      total: 0,
      last_page: 1
    }
  }),

  actions: {
    async fetchUsers({ page = 1, search = '' }) {
      const response = await getUsers(page, search)
      this.users = response.items
      this.pagination = {
        current_page: response.current_page,
        total: response.total,
        last_page: response.last_page
      }
    },

    async addUser(payload: Omit<User, 'id'>) {
      const newUser = await createUser(payload)
      this.users.unshift(newUser) // opcional: atualiza localmente
    },

    async updateUser(id: number, payload: Omit<User, 'id'>) {
      const updated = await updateUser(id, payload)
      const index = this.users.findIndex(user => user.id === id)
      if (index !== -1) this.users[index] = updated
    },

    async removeUser(id: number) {
      await deleteUser(id)
      this.users = this.users.filter(user => user.id !== id)
    },
    async resetPassword(id: number) {
      return await resetPasswordService(id);
    },
    async changePassword(payload: ChangePasswordType) {
      return await changePasswordService(payload);
    }
  }
})

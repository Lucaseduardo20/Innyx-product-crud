import { defineStore } from 'pinia'
import type { Preview } from '@/types/user'
import { previewService } from '@/services/user'

export const useAuthStore = defineStore('auth', {
  state: () => ({
    token: null as string | null,
    user: null as Record<string, any> | null,
    preview: {} as Preview,
  }),
  actions: {
    setAuth(token: string, user: any) {
      this.token = token
      this.user = user
      localStorage.setItem('token', token)
      localStorage.setItem('user', JSON.stringify(user));
    },
    setPreview(preview: Preview) {
      this.preview = preview;
    },
    async fetchPreview() {
      const data = await previewService() as Preview;
      this.setPreview(data);
      return data;
    },
    logout() {
      this.token = null
      this.user = null
    },
  },
})

<template>
  <AppLayout>
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-2">
      <CustomCard title="Produtos cadastrados" icon="📦" :value="preview.products" bg-color="bg-senary"
        text-color="text-quaternary" />
      <CustomCard title="Usuários registrados" icon="👤" :value="preview.users" v-if="auth.user?.is_admin"
        bg-color="bg-quinary-100" text-color="text-quaternary-800" />
      <CustomCard title="Última atualização" icon="⏰" :value="preview.last_updated" bg-color="bg-tertiary-50"
        text-color="text-quaternary-700" />
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import axios from 'axios'
import { toast } from 'vue3-toastify'
import { useAuthStore } from '@/stores/auth'
import AppLayout from '@/layouts/AppLayout.vue'
import CustomCard from '@/components/CustomCard.vue'

const preview = ref({
  products: 0,
  users: 0,
  last_updated: ''
})

const auth = useAuthStore()

const fetchPreview = async () => {
  try {
    const res = await axios.get('/api/preview')
    preview.value = res.data
  } catch (err) {
    toast.error('Erro ao carregar dados do dashboard')
    console.error(err)
  }
}

onMounted(() => {
  fetchPreview()
})
</script>

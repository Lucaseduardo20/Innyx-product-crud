<script setup lang="ts">
import { onMounted } from 'vue';
import { useAuthStore } from './stores/auth';

const auth = useAuthStore();
const setUser = () => {
  const user = JSON.parse(localStorage.getItem('user') as string);
  if (user) {
    auth.setAuth(localStorage.getItem('token') as string, user);
  }
}

onMounted(() => {
  setUser();
})
</script>

<template>
  <router-view />
</template>

<style scoped></style>

import Dashboard from '@/views/Dashboard.vue'
import Login from '@/views/Login.vue'
import Products from '@/views/Products.vue'
import Users from '@/views/Users.vue'
import { createRouter, createWebHistory } from 'vue-router'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'login',
      component: Login,
    },
    {
      path: '/dashboard',
      name: 'dashboard',
      component: Dashboard
    },
    {
      path: '/products',
      name: 'products',
      component: Products
    },
    {
      path: '/users',
      name: 'users',
      component: Users
    }
  ],
})

export default router

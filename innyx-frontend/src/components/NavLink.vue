<!-- src/components/NavLink.vue -->
<template>
    <router-link :to="to" class="flex items-center space-x-3 p-3 rounded-lg transition-all duration-200"
        :class="classes">
        <span class="text-xl">{{ icon }}</span>
        <span class="font-medium" v-if="!mobile">{{ label }}</span>
        <span class="text-xs mt-1" v-if="mobile">{{ label }}</span>
    </router-link>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import { useRoute } from 'vue-router'

const props = defineProps<{
    to: string
    icon: string
    label: string
    mobile?: boolean
}>()

const route = useRoute()

const classes = computed(() => {
    const isActive = route.path === props.to
    if (isActive) {
        return 'bg-primary bg-opacity-10 text-primary dark:bg-quinary dark:bg-opacity-20 dark:text-quinary'
    }
    return 'text-quaternary-600 hover:bg-senary dark:text-quinary-300 dark:hover:bg-quaternary-700'
})
</script>

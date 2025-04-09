<template>
    <div v-if="isOpen" class="fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center p-4">
        <div class="bg-white p-6 rounded-xl w-full max-w-md dark:bg-quaternary-900">
            <h2 class="text-lg font-semibold mb-4 dark:text-white">
                {{ isEditing ? 'Editar Produto' : 'Novo Produto' }}
            </h2>

            <form @submit.prevent="submit" class="space-y-4">
                <input v-model="form.name" type="text" placeholder="Nome"
                    class="w-full p-2 border rounded dark:bg-quaternary-800 dark:text-white" />
                <textarea v-model="form.description" placeholder="Descrição"
                    class="w-full p-2 border rounded dark:bg-quaternary-800 dark:text-white" />

                <input v-model.number="form.price" type="number" placeholder="Preço"
                    class="w-full p-2 border rounded dark:bg-quaternary-800 dark:text-white" />

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">
                        Imagem do Produto
                    </label>
                    <input type="file" accept="image/*" @change="onFileChange"
                        class="block w-full text-sm text-gray-900 dark:text-white file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary file:text-white hover:file:bg-primary/80" />
                    <div v-if="imagePreview" class="mt-2">
                        <img :src="imagePreview" alt="Prévia da imagem"
                            class="w-full h-48 object-cover rounded-lg border" />
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">
                        Categoria
                    </label>
                    <select v-model="form.category_id"
                        class="w-full p-2 border rounded dark:bg-quaternary-800 dark:text-white">
                        <option disabled value="">Selecione uma categoria</option>
                        <option v-for="category in categories" :key="category.id" :value="category.id">
                            {{ category.name }}
                        </option>
                    </select>
                </div>

                <div class="flex justify-end gap-2 pt-4">
                    <button type="button" @click="$emit('close')" class="text-gray-500">
                        Cancelar
                    </button>
                    <button type="submit" class="bg-primary text-white px-4 py-2 rounded">
                        Salvar
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup lang="ts">
import { computed, reactive, ref, watch } from 'vue'
import type { Product } from '@/types/product'
import type { Category } from '@/types/category'

const emit = defineEmits(['submit', 'close'])

const props = defineProps<{
    isOpen: boolean
    initialData?: Product | null
    categories: Category[]
}>()

const form = reactive({
    name: '',
    description: '',
    price: 0,
    category_id: '',
    image: null as File | null,
})

const imagePreview = ref<string | null>(null)

const isEditing = computed(() => !!props.initialData)

watch(
    () => props.initialData,
    (newVal) => {
        if (newVal) {
            form.name = newVal.name
            form.description = newVal.description
            form.price = newVal.price
            form.category_id = String(newVal.category_id)
            imagePreview.value = newVal.image_url || null
        } else {
            form.name = ''
            form.description = ''
            form.price = 0
            form.category_id = ''
            form.image = null
            imagePreview.value = null
        }
    },
    { immediate: true }
)

function onFileChange(e: Event) {
    const file = (e.target as HTMLInputElement).files?.[0]
    if (file) {
        form.image = file
        imagePreview.value = URL.createObjectURL(file)
    }
}

function submit() {
    const payload = new FormData()
    payload.append('name', form.name)
    payload.append('description', form.description)
    payload.append('price', form.price.toString())
    payload.append('category_id', form.category_id)
    if (form.image) {
        payload.append('image', form.image)
    }

    emit('submit', payload)
}
</script>
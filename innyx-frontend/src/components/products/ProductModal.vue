<template>
    <div v-if="isOpen" class="fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center p-4">
        <div class="bg-white p-6 rounded-xl w-full max-w-md dark:bg-quaternary-900">
            <h2 class="text-lg font-semibold mb-4 text-primary">
                {{ isEditing ? 'Editar Produto' : 'Novo Produto' }}
            </h2>

            <form @submit.prevent="submit" class="space-y-4">
                <div>
                    <input v-model="form.name" type="text" placeholder="Nome"
                        class="w-full p-2 border rounded dark:bg-quaternary-800 text-black" />
                </div>

                <div>
                    <textarea v-model="form.description" placeholder="Descrição"
                        class="w-full p-2 border rounded dark:bg-quaternary-800 text-black" />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Preço (R$)</label>
                    <div class="relative flex items-center">
                        <input v-model="formattedPrice" @input="handlePriceInput" @blur="formatPrice" placeholder="0,00"
                            class="w-full p-2 border rounded dark:bg-quaternary-800 text-black" />
                    </div>
                    <p v-if="priceError" class="text-red-500 text-sm mt-1">{{ priceError }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Data de Validade</label>
                    <input v-model="form.valid_until" type="date" :min="minDate"
                        class="w-full p-2 border rounded dark:bg-quaternary-800 text-black" />
                    <p v-if="dateError" class="text-red-500 text-sm mt-1">{{ dateError }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Imagem do Produto
                    </label>
                    <input type="file" accept="image/*" @change="onFileChange"
                        class="block w-full text-sm text-gray-900 text-black file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary file:text-white hover:file:bg-primary/80" />
                    <div v-if="imagePreview" class="mt-2">
                        <img :src="imagePreview" alt="Prévia da imagem"
                            class="w-full h-48 object-cover rounded-lg border" />
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Categoria
                    </label>
                    <select v-model="form.category_id"
                        class="w-full p-2 border rounded dark:bg-quaternary-800 text-black">
                        <option disabled value="">Selecione uma categoria</option>
                        <option v-for="category in categories" :key="category.id" :value="category.id">
                            {{ category.name }}
                        </option>
                    </select>
                </div>

                <div class="flex justify-end gap-2 pt-4">
                    <button type="button" @click="closeModal" class="text-gray-500">
                        Cancelar
                    </button>
                    <button type="submit"
                        class="bg-primary text-white w-20 h-[32px] rounded flex justify-center items-center">
                        <Spinner :loading="loading" />
                        {{ loading ? '' : 'Salvar' }}
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
import { useAuthStore } from '@/stores/auth';
import Spinner from '../shared/Spinner.vue';


const emit = defineEmits(['submit', 'close'])
const loading = ref(false);

const props = defineProps<{
    isOpen: boolean
    initialData?: Product | null
    categories: Category[]
}>()

const form = reactive({
    name: '',
    description: '',
    price: 0,
    valid_until: '',
    category_id: '',
    image: null as File | null,
})

const imagePreview = ref<string | null>(null)
const formattedPrice = ref('')
const priceError = ref('')
const dateError = ref('')
const auth = useAuthStore();

const isEditing = computed(() => !!props.initialData)

const minDate = computed(() => {
    const today = new Date()
    return today.toISOString().split('T')[0]
})

watch(
    () => props.initialData,
    (newVal) => {
        if (newVal) {
            form.name = newVal.name || ''
            form.description = newVal.description || ''
            form.price = newVal.price || 0
            formattedPrice.value = formatNumberToCurrency(newVal.price || 0)
            form.valid_until = newVal.valid_until || ''
            form.category_id = String(newVal.category_id || '')
            imagePreview.value = newVal.image || null
        } else {
            resetForm()
        }
    },
    { immediate: true }
)


function resetForm() {
    form.name = ''
    form.description = ''
    form.price = 0
    formattedPrice.value = ''
    form.valid_until = ''
    form.category_id = ''
    form.image = null
    imagePreview.value = null
    priceError.value = ''
    dateError.value = ''
}

function formatNumberToCurrency(value: number): string {
    return value.toLocaleString('pt-BR', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    })
}

function handlePriceInput(event: Event) {
    let value = (event.target as HTMLInputElement).value
    value = value.replace(/[^\d,]/g, '')
    const commaCount = value.split(',').length - 1
    if (commaCount > 1) {
        value = value.replace(/,+$/, '')
    }
    formattedPrice.value = value
}

function formatPrice() {
    let value = formattedPrice.value
    if (!value.includes(',')) {
        value += ',00'
    }
    const [whole, decimal] = value.split(',')
    const formattedDecimal = (decimal || '').padEnd(2, '0').slice(0, 2)
    formattedPrice.value = `${whole || '0'},${formattedDecimal}`

    const numericValue = parseFloat(
        formattedPrice.value.replace('.', '').replace(',', '.')
    )

    if (!isNaN(numericValue)) {
        form.price = numericValue
        priceError.value = ''
    } else {
        priceError.value = 'Valor inválido'
    }
}

function validateDate() {
    if (!form.valid_until) {
        dateError.value = ''
        return true
    }

    const selectedDate = new Date(form.valid_until)
    const today = new Date()
    today.setHours(0, 0, 0, 0)

    if (selectedDate < today) {
        dateError.value = 'A data não pode ser anterior a hoje'
        return false
    }

    dateError.value = ''
    return true
}

function onFileChange(e: Event) {
    const file = (e.target as HTMLInputElement).files?.[0]
    if (file) {
        form.image = file
        imagePreview.value = URL.createObjectURL(file)
    }
}

function closeModal() {
    resetForm()
    emit('close')
}

function submit() {
    if (!validateDate()) return;

    loading.value = true;

    const payload = new FormData();
    payload.append('name', form.name);
    payload.append('description', form.description);
    payload.append('price', form.price.toString());
    payload.append('valid_until', form.valid_until);
    payload.append('category_id', form.category_id);
    payload.append('user_id', auth.user?.id);

    if (form.image) {
        payload.append('image_path', form.image);
    }

    const isEdit = isEditing.value;
    const productId = props.initialData?.id || null;

    setTimeout(() => {
        emit('submit', {
            payload,
            isEdit,
            id: productId,
        });
        loading.value = false;
        closeModal();
    }, 1500);
}

</script>
import { api } from './api'
import type { User } from '@/types/user'

interface PaginatedUserResponse {
    items: User[]
    current_page: number
    total: number
    last_page: number
}

export const previewService = async () => {
    return await api.get('/preview').then((res) => {
        return res.data;
    }).catch((err) => {
        return err;
    })
}

export async function getUsers(page = 1, search = ''): Promise<PaginatedUserResponse> {
    const { data } = await api.get('/users', {
        params: { page, search }
    })
    return data
}

export async function createUser(payload: Omit<User, 'id'>): Promise<User> {
    const { data } = await api.post('/users', payload)
    return data
}

export async function updateUser(id: number, payload: Omit<User, 'id'>): Promise<User> {
    const { data } = await api.put(`/users/${id}`, payload)
    return data
}

export async function deleteUser(id: number): Promise<void> {
    await api.delete(`/users/${id}`)
}

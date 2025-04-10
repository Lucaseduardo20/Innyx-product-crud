import { api } from "./api";

export const getCategories = async () => {
    return await api.get('/categories').then((res) => {
        return res
    }).catch((err) => err)
}

export const setCategories = async (name: string) => {
    return await api.post('/categories', { name }).then((res) => {
        return res
    }).catch((err) => err)
}

export const deleteCategory = async (id: number) => {
    return await api.delete(`/categories/${id}`).then((res) => {
        return res
    }).catch((err) => err)
}

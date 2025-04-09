import { api } from "./api";

export const getCategories = async () => {
    return await api.get('/categories').then((res) => {
        return res
    }).catch((err) => err)
}
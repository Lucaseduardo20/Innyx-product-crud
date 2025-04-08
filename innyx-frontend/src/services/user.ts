import { api } from "./api";

export const previewService = async () => {
    return await api.get('/preview').then((res) => {
        return res.data;
    }).catch((err) => {
        return err;
    })
}
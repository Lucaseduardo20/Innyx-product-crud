import { api } from "./api";

export const loginService = async (data: {email: string, password: string}) => {
    return await api.post('/login', data).then((res) => {
        return res;
    }).catch((err) => {
        console.log(err.message);
        return err;
    })
}
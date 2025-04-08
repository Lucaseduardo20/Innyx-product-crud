import axios from "axios";
import type { AxiosInstance } from "axios";

const url = 'https://b4e2-2804-1b1-f800-5f83-713f-e064-f80c-e8fd.ngrok-free.app/';
export const api: AxiosInstance = axios.create({
    baseURL: url + "api/",
    headers: {
        "Content-Type": "application/json"
    },
    withCredentials: true
});

api.interceptors.request.use(config => {
    const token = localStorage.getItem("token");
    if (token) {
        config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
}, error => Promise.reject(error));

export type User = {
    id?: number,
    name: string,
    email: string,
    role_name?: string,
    is_admin: boolean
}

export type Preview = {
    users: number,
    products: number,
    last_updated: string
}
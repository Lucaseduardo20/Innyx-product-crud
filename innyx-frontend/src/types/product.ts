export interface Product {
  id: number;
  user_id: number,
  name: string;
  description: string;
  price: number;
  category_id: number,
  valid_until: string,
  image: string,
}

export interface ProductPayload {
  name: string
  description: string
  price: number
  category_id: number
  image?: File
}

export interface ProductFilters {
  search?: string;
  page?: number;
  per_page?: number;
}

export interface PaginatedResponse<T> {
  data: T[];
  current_page: number;
  last_page: number;
  total: number;
}

export interface ProductResponseList {
  products: Product[];
  current_page: number;
  last_page: number;
  total: number;
}
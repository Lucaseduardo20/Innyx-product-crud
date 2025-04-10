<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:50'],
            'user_id' => ['required', 'exists:users,id'],
            'description' => ['nullable', 'string', 'max:200'],
            'price' => ['required', 'numeric'],
            'valid_until' => ['nullable','date'],
            'category_id' => ['required', 'exists:categories,id'],
            'image' => ['nullable', 'image', 'max:2048'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}

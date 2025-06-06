<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'email' => ['required', 'email', 'unique:users,email'],
            'role_name' => ['nullable', 'exists:roles,name']
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}

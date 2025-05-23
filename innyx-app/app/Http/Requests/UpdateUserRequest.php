<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($this->user->id)
            ],
            'role_name' => ['nullable', 'exists:roles,name']
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}

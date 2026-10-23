<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ToggleUserActiveRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'is_active' => 'required|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'is_active.required' => 'User status is required.',
            'is_active.boolean' => 'User status must be true or false.',
        ];
    }
}

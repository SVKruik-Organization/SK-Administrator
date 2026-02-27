<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<int, string>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|array',
            'description' => 'nullable|array',
            'position' => 'required|integer|min:0',
            'language' => 'required|string|max:2|min:2',
        ];
    }
}

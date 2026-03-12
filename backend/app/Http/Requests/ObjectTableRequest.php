<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ObjectTableRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<int, string>|string>
     */
    public function rules(): array
    {
        return [
            'page' => 'required|integer|min:1',
            'perPage' => 'required|integer|min:1',
        ];
    }

    public function prepareForValidation(): void
    {
        $this->merge([
            'page' => is_numeric($this->get('page')) ? (int) $this->get('page') : 1,
            'perPage' => is_numeric($this->get('perPage')) ? (int) $this->get('perPage') : 15,
        ]);
    }
}

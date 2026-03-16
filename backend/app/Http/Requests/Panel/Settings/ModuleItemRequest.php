<?php

declare(strict_types=1);

namespace App\Http\Requests\Panel\Settings;

use Illuminate\Foundation\Http\FormRequest;

class ModuleItemRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<int, string>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|array|filled',
            'name.en' => 'required|string|max:255',
            'name.nl' => 'required|string|max:255',
            'icon' => 'nullable|string|max:255|starts_with:fa-',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'name' => [
                'en' => $this->name['en'],
                'nl' => $this->name['nl'],
            ],
        ]);
    }
}

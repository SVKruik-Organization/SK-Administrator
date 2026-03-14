<?php

declare(strict_types=1);

namespace App\Http\Requests\Panel\Settings;

use Illuminate\Foundation\Http\FormRequest;

class ModuleRequest extends FormRequest
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
            'icon' => ['required', 'string', 'max:255', 'starts_with:fa-'],
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
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

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.en.required' => 'De Engelse naam is verplicht.',
            'name.nl.required' => 'De Nederlandse naam is verplicht.',
            'name.en.max' => 'De Engelse naam mag maximaal 255 tekens lang zijn.',
            'name.nl.max' => 'De Nederlandse naam mag maximaal 255 tekens lang zijn.',
            'icon.required' => 'Het pictogram is verplicht.',
            'icon.max' => 'Het pictogram mag maximaal 255 tekens lang zijn.',
            'icon.starts_with' => 'Het pictogram moet een Font Awesome icon zijn.',
        ];
    }
}

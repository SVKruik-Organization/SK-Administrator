<?php

declare(strict_types=1);

namespace App\Http\Requests\Panel\Settings;

use Illuminate\Foundation\Http\FormRequest;

class ModuleRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|array',
            'icon' => ['required', 'string', 'max:255', 'starts_with:fa-'],
        ];
    }
}
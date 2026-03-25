<?php

namespace App\Http\Requests\Singers;

use Illuminate\Foundation\Http\FormRequest;

class SingerUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['nullable', 'string', 'max:255'],
            'age' => ['nullable', 'integer', 'min:0', 'max:150'],
        ];
    }
}

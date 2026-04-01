<?php

namespace App\Http\Requests\Files;

use Illuminate\Foundation\Http\FormRequest;

class FileStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'file' => ['required', 'file', 'max:10240'],
            'description' => ['nullable', 'string', 'max:65535'],
            'song_id' => ['nullable', 'integer', 'exists:songs,id'],
        ];
    }
}

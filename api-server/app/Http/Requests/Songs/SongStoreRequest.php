<?php

namespace App\Http\Requests\Songs;

use Illuminate\Foundation\Http\FormRequest;

class SongStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'singer_id' => ['required', 'integer', 'exists:singers,id'],
            'year' => ['required', 'integer'],
        ];
    }
}

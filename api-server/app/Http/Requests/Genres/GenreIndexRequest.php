<?php

namespace App\Http\Requests\Genres;

use App\Domain\Enums\GenreSortByEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GenreIndexRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'page' => ['required', 'integer', 'min:1'],
            'size' => ['required', 'integer', 'min:1'],
            'sortBy' => ['required', 'string', Rule::in(array_map(fn (GenreSortByEnum $c) => $c->value, GenreSortByEnum::cases()))],
            'sortOrder' => ['required', 'string', 'in:asc,desc'],
        ];
    }
}

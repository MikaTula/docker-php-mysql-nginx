<?php

namespace App\Http\Requests\Files;

use App\Domain\Enums\FileSortByEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FileIndexRequest extends FormRequest
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
            'sortBy' => ['required', 'string', Rule::in(array_map(fn (FileSortByEnum $c) => $c->value, FileSortByEnum::cases()))],
            'sortOrder' => ['required', 'string', 'in:asc,desc'],
        ];
    }
}

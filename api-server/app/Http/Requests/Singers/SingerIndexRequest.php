<?php

namespace App\Http\Requests\Singers;

use App\Domain\Enums\SingerSortByEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SingerIndexRequest extends FormRequest
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
            'sortBy' => ['required', 'string', Rule::in(array_map(fn (SingerSortByEnum $c) => $c->value, SingerSortByEnum::cases()))],
            'sortOrder' => ['required', 'string', 'in:asc,desc'],
        ];
    }
}

<?php

namespace App\Mappers;

use App\Domain\Pagination\PaginationInModel;
use App\Http\Requests\Common\PaginationRequest;

class PaginationMapper
{
    public static function mapFromRequest(PaginationRequest $request): PaginationInModel
    {
        return new PaginationInModel(
            $request->get('page'),
            $request->get('size'),
            $request->get('sortBy'),
            $request->get('sortOrder'),
        );
    }

    /**
     * @param  array{page: int|string, size: int|string, sortBy: string, sortOrder: string}  $data
     */
    public static function mapFromValidated(array $data): PaginationInModel
    {
        return new PaginationInModel(
            (int) $data['page'],
            (int) $data['size'],
            (string) $data['sortBy'],
            (string) $data['sortOrder'],
        );
    }
}

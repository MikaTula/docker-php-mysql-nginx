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

}

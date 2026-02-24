<?php

use Illuminate\Pagination\AbstractPaginator;

function jsonPaginate($request): array
{
    return $request instanceof AbstractPaginator
    ? [
        'links' => [
            'first' => $request->url(1),
            'prev' => $request->previousPageUrl(),
            'next' => $request->nextPageUrl(),
        ],
        'meta' => [
            'current_page' => $request->currentPage(),
            'per_page' => $request->perPage(),
            'total' => $request->total(),
        ],
    ]
    : [];
}

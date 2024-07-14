<?php

namespace App\Trait;

use Illuminate\Pagination\LengthAwarePaginator;

trait PaginationTrait
{
    protected function getPaginationData(LengthAwarePaginator $paginator): array
    {
        return [
            'page' => $paginator->currentPage(),
            'total_pages' => $paginator->lastPage(),
            'total_users' => $paginator->total(),
            'count' => $paginator->count(),
            'links' => [
                'next_url' => $paginator->nextPageUrl() . '&count=' . $paginator->perPage(),
                'prev_url' => $paginator->previousPageUrl() . '&count=' . $paginator->perPage(),
            ],
        ];
    }
}
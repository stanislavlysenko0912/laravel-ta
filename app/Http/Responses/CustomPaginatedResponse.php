<?php

namespace App\Http\Responses;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'PaginatedResource',
    properties: [
        new OA\Property(property: 'links', properties: [
            new OA\Property(property: 'next_url', type: 'string', nullable: true),
            new OA\Property(property: 'prev_url', type: 'string', nullable: true),
        ], type: 'object'),
        new OA\Property(property: 'page', type: 'integer', example: 1),
        new OA\Property(property: 'count', type: 'integer', example: 5),
        new OA\Property(property: 'total_pages', type: 'integer', example: 10),
    ],
)]
class CustomPaginatedResponse implements Responsable
{
    protected LengthAwarePaginator $paginator;
    protected string $resourceClass;
    protected string $resourceName;

    public function __construct(LengthAwarePaginator $paginator, string $resourceClass, string $resourceName = 'data')
    {
        $this->paginator = $paginator;
        $this->resourceClass = $resourceClass;
        $this->resourceName = $resourceName;
    }

    public function toResponse($request): JsonResponse
    {
        $resourceClass = $this->resourceClass;

        $nextPageUrl = $this->paginator->nextPageUrl();
        $prevPageUrl = $this->paginator->previousPageUrl();

        $data = [
            'page' => $this->paginator->currentPage(),
            'total_pages' => $this->paginator->lastPage(),
            'total_' . $this->resourceName => $this->paginator->total(),
            'count' => $this->paginator->count(),
            'links' => [
                'next_url' => is_null($nextPageUrl) ? null : $nextPageUrl . '&count=' . $this->paginator->perPage(),
                'prev_url' => is_null($prevPageUrl) ? null : $prevPageUrl . '&count=' . $this->paginator->perPage(),
            ],
            $this->resourceName => Collection::make($this->paginator->items())->map(function ($item) use ($resourceClass) {
                return new $resourceClass($item);
            })->values()->all(),
        ];

        return new JsonResponse($data);
    }
}
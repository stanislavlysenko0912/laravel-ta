<?php

namespace App\Http\Resources\V1\User;

use App\Trait\PaginationTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'UserCollectionV1',
    properties: [
        new OA\Property(property: 'page', type: 'integer', example: 1),
        new OA\Property(property: 'total_pages', type: 'integer', example: 10),
        new OA\Property(property: 'total_users', type: 'integer', example: 47),
        new OA\Property(property: 'count', type: 'integer', example: 5),
        new OA\Property(property: 'links', properties: [
            new OA\Property(property: 'next_url', type: 'string', nullable: true),
            new OA\Property(property: 'prev_url', type: 'string', nullable: true),
        ], type: 'object'),
        new OA\Property(
            property: 'users',
            description: 'A collection of users',
            type: 'array',
            items: new OA\Items(
                ref: '#/components/schemas/UserResourceV1'
            )
        ),
    ],
    additionalProperties: false
)]
class UserCollectionV1 extends ResourceCollection
{
    use PaginationTrait;

    public function toArray($request): array
    {
        $paginationData = $this->getPaginationData($this->resource);

        return $paginationData + [
            'users' => UserResourceV1::collection($this->collection),
        ];
    }
}

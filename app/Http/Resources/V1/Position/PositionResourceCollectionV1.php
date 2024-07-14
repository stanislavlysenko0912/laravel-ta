<?php

namespace App\Http\Resources\V1\Position;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'PositionResourceCollectionV1',
    properties: [
        new OA\Property(
            property: 'positions',
            description: 'A collection of positions',
            type: 'array',
            items: new OA\Items(
                ref: '#/components/schemas/PositionResourceV1',
            )
        ),
    ],
    additionalProperties: false
)]
/** @see \App\Models\Position */
class PositionResourceCollectionV1 extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'positions' => $this->collection,
        ];
    }
}

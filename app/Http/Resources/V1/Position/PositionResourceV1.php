<?php

namespace App\Http\Resources\V1\Position;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'PositionResourceV1',
    properties: [
        new OA\Property(
            property: 'id',
            description: 'ID of the position',
            type: 'integer',
            example: '2',
        ),
        new OA\Property(
            property: 'name',
            description: 'Name of the position',
            type: 'string',
            example: 'Security',
        )
    ],
    additionalProperties: false
)]
/** @see \App\Models\Position */
class PositionResourceV1 extends JsonResource
{

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
        ];
    }
}

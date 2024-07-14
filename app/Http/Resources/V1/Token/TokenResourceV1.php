<?php

namespace App\Http\Resources\V1\Token;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'TokenResourceV1',
    properties: [
        new OA\Property(
            property: 'token',
            description: 'JWT token, used for authentication',
            type: 'string',
            example: 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiIsImp0aSI6IjAxOTA5MWYzLTJmYmUtNzRmYS1hMGVlLTA4ZTk1NmJiMjdmMSJ9.eyJpYXQiOjE3MjA0MzU1NTIuMTg3NDc1LCJleHAiOjE3MjA0Mzc5NTIuMTg3NDc1fQ.XygWBEsXp1GxLGrAygmNgPP9KotIxAQt6CWMHi0CZ88'
        )
    ],
    additionalProperties: false
)]
class TokenResourceV1 extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'token' => $this->resource,
        ];
    }
}

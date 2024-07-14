<?php

namespace App\Http\Resources\V1\User;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Attributes as OA;
use Storage;

#[OA\Schema(
    schema: 'UserResourceV1',
    properties: [
        new OA\Property(property: 'name', description: 'User name', type: 'string', example: 'John Doe'),
        new OA\Property(property: 'email', description: 'User email', type: 'string', example: 'john@example.net'),
        new OA\Property(property: 'phone', description: 'User phone', type: 'string', example: '+380950104161'),
        new OA\Property(property: 'registration_timestamp', description: 'User registration timestamp', type: 'integer', example: '1630512000'),
        new OA\Property(property: 'position_id', description: 'User position id', type: 'integer', example: '1'),
        new OA\Property(property: 'position', description: 'User position name', type: 'string', example: 'Security'),
        new OA\Property(property: 'photo', description: 'User photo', type: 'string', format: 'uri', example: 'http://example.net/storage/avatars/123.jpg'),
    ],
    additionalProperties: false
)]
/** @mixin User */
class UserResourceV1 extends JsonResource
{
    public static $wrap = 'user';

    public function toArray(Request $request): array
    {
        if (!$this->relationLoaded('position')) {
            $this->load('position');
        }

        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'registration_timestamp' => $this->registration_timestamp,
            'position_id' => (int)$this->position_id,
            'position' => $this->whenLoaded('position', fn() => $this->position->name),
            'photo' => Storage::url($this->photo),
        ];
    }
}

<?php

namespace App\Http\Requests\V1\User;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;


#[OA\Schema(
    schema: 'UserStoreRequestV1',
    required: ['name', 'email', 'phone', 'position_id', 'photo'],
    properties: [
        new OA\Property(property: 'name', description: 'User name', type: 'string', maxLength: 60, minLength: 2),
        new OA\Property(property: 'email', description: 'User email', type: 'string', format: 'email', maxLength: 255),
        new OA\Property(
            property: 'phone',
            description: 'User phone',
            type: 'string',
            maxLength: 15,
            minLength: 13,
            example: '+380501234567',
        ),
        new OA\Property(property: 'position_id', description: 'Position id', type: 'integer', example: 1),
        new OA\Property(property: 'photo', description: 'User photo', type: 'string', format: 'binary'),
    ],
)]
class UserStoreRequestV1 extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:60', 'min:2'],
            'email' => ['required', 'email:rfc', 'max:255', 'unique:users,email'],
            'phone' => ['required', 'min:13', 'max:15', 'starts_with:+380', 'regex:/^\+380\d{9}$/', 'unique:users,phone'],
            'position_id' => ['required', 'exists:positions,id'],
            'photo' => 'nullable|image|mimes:jpeg,jpg|max:5120|dimensions:min_width=70,min_height=70',
        ];
    }
}

<?php

namespace App\Http\Requests\V1\User;

use Illuminate\Foundation\Http\FormRequest;

class UsersListRequestV1 extends FormRequest
{
    public function rules(): array
    {
        return [
            'page' => 'integer|min:1',
            'count' => 'integer|min:1',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'page' => $this->query('page', 1),
            'count' => $this->query('count', 5),
        ]);
    }
}

<?php

namespace App\Http\Controllers\V1\Token;

use App\Http\Controllers\V1\V1Controller;
use App\Http\Resources\V1\Token\TokenResourceV1;
use App\Services\JwtService;
use OpenAPI\Operation\ApiGet;
use OpenAPI\Responses\ResponseJsonSuccess;

class TokenController extends V1Controller
{
    private JwtService $jwtService;

    public function __construct(JwtService $jwtService)
    {
        $this->jwtService = $jwtService;
    }

    #[ApiGet(
        path: '/token', summary: 'Get a new token',
        tags: ['token'],
        description: '## Method returns a token that is required to register a new user.
- The token is valid for **40 minutes** and can be used for only **one** request.
- For the next registration, you will need to get a **new one**.',
    )]
    #[ResponseJsonSuccess(TokenResourceV1::class)]
    public function __invoke(): TokenResourceV1
    {
        $token = $this->jwtService->createToken();

        return new TokenResourceV1($token);
    }
}

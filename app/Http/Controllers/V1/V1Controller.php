<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use OpenApi\Attributes as OA;

#[OA\Server(url: "http://localhost:8000/api/v1", description: "Local Development Server")]
#[OA\Server(url: "http://localhost:81/", description: "Test Server")]
#[OA\SecurityScheme(
    securityScheme: 'tokenAuth',
    type: 'apiKey',
    name: 'Authorization',
    in: 'header',
)]
#[OA\Tag(name: "users", description: null)]
#[OA\Tag(name: "positions", description: null)]
#[OA\Tag(name: "token", description: null)]
class V1Controller extends Controller
{

}

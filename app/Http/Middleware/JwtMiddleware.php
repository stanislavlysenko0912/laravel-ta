<?php

namespace App\Http\Middleware;

use App\Exceptions\MessageException;
use App\Services\JwtService;
use Closure;

class JwtMiddleware
{
    private JwtService $jwtService;

    public function __construct(JwtService $jwtService)
    {
        $this->jwtService = $jwtService;
    }

    /**
     * @throws MessageException
     */
    public function handle($request, Closure $next)
    {
        $token = $request->header('authorization');

        if (!$token || !$this->jwtService->validateToken($token)) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        return $next($request);
    }
}
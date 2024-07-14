<?php

namespace App\Http\Middleware;

use App\Services\JwtService;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AddStatusToResponseMiddleware
{
    private JwtService $jwtService;

    public function __construct(JwtService $jwtService)
    {
        $this->jwtService = $jwtService;
    }


    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if ($response instanceof JsonResponse) {
            $data = $response->getData(true);

            // This will be better
            // $data['success'] = $response->isSuccessful();

            // But this look`s pretty good in result (success first)
            $data = array_merge(
                ['success' => $response->isSuccessful()],
                $data
            );

            $response->setData($data);
        }

        $token = $request->header('authorization');

        if ($token && $response->isSuccessful()) {
            $this->jwtService->setTokenToUsed($token);
        }

        return $response;
    }
}

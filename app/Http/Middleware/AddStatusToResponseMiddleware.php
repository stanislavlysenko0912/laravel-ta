<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AddStatusToResponseMiddleware
{
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

        return $response;
    }
}

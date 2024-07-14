<?php

namespace App\Http\Controllers\V1\Position;

use App\Exceptions\MessageException;
use App\Http\Controllers\V1\V1Controller;
use App\Http\Resources\V1\Position\PositionResourceCollectionV1;
use App\Models\Position;
use OpenAPI\Operation\ApiGet;
use OpenAPI\Responses\ResponseError;
use OpenAPI\Responses\ResponseJsonSuccess;

class PositionController extends V1Controller
{
    #[ApiGet(
        path: '/positions', summary: 'Get users positions',
        tags: ['positions'],
        description: '## Returns a list of all available users positions.',
        auth: true
    )]
    #[ResponseJsonSuccess(PositionResourceCollectionV1::class)]
    #[ResponseError(404, 'No positions found')]
    /** @throws MessageException */
    public function __invoke(): PositionResourceCollectionV1
    {
        $positions = Position::query()->get();

        if ($positions->isEmpty()) {
            throw new MessageException('No positions found', 404);
        }

        return PositionResourceCollectionV1::make($positions);
    }
}

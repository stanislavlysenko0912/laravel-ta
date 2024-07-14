<?php

namespace OpenAPI\Responses;

use OpenApi\Attributes as OA;

#[\Attribute]
class ResponseError extends OA\Response
{
    public function __construct($status = 400, $description = 'Error message')
    {
        $content = new OA\JsonContent(
            allOf: [
                new OA\Schema(properties: [
                    new OA\Property(
                        property: 'success',
                        description: 'The request was not successful',
                        type: 'boolean',
                        example: false
                    ),
                    new OA\Property(
                        property: 'message',
                        description: 'The error message',
                        type: 'string',
                        example: $description
                    ),
                ]),
            ]
        );

        parent::__construct(
            response: $status,
            description: $description,
            content: $content
        );
    }
}

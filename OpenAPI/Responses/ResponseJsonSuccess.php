<?php

namespace OpenAPI\Responses;

use OpenApi\Attributes as OA;
use ReflectionClass;
use ReflectionException;

#[\Attribute]
class ResponseJsonSuccess extends OA\Response
{
    /**
     * @throws ReflectionException
     */
    public function __construct(
        string $resource,
        int $response = 200,
        bool $success = true,
        string $description = 'Success',
        string|null $wrap = null,
        string|null $paginated = null
    ) {
        $className = (new ReflectionClass($resource))->getShortName();
        $ref = "#/components/schemas/{$className}";

        $content = new OA\JsonContent(
            allOf: [
                new OA\Schema(properties: [
                    new OA\Property(
                        property: 'success',
                        description: 'The request was successful',
                        type: 'boolean',
                        example: $success
                    ),
                ]),
                $wrap != null ? new OA\Schema(
                    properties: [
                        new OA\Property(
                            property: $wrap,
                            ref: $ref
                        )
                    ]
                ) : ($paginated != null ? new OA\Schema(
                    properties: [
                        new OA\Property(property: 'total_' . $paginated, type: 'integer', example: 5),
                        new OA\Property(
                            property: $paginated,
                            type: 'array',
                            items: new OA\Items(
                                ref: $ref
                            )
                        ),
                    ],
                    allOf: [
                        new OA\Schema(
                            ref: '#/components/schemas/PaginatedResource'
                        )
                    ]
                ) : new OA\Schema(
                    ref: $ref
                ))
            ]
        );

        parent::__construct(
            response: $response,
            description: $description,
            content: $content
        );
    }
}

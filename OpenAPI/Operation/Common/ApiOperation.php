<?php

namespace OpenAPI\Operation\Common;

use OpenApi\Attributes as OA;
use OpenApi\Generator;
use OpenAPI\Responses\ResponseError;
use ReflectionClass;
use ReflectionProperty;

trait ApiOperation
{
    public function __construct(
        string $path,
        string $summary,
        array $tags,
        string|null $description = null,
        ?bool $auth = false,
        array $parameters = [],
        string|null $requestClass = null,
        string $requestType = 'form'
    ) {
        $responses = $auth ? [new ResponseError(401, "Unauthorized")] : [];

        if ($requestClass !== null) {
            $requestBody = $this->generateRequestBody($requestClass, $requestType);
            $responses[] = $this->generateValidationErrorResponse($requestClass);
        }

        parent::__construct([
            'path' => $path,
            'description' => $description,
            'summary' => $summary,
            'security' => $auth ? [['tokenAuth' => []]] : Generator::UNDEFINED,
            'tags' => $tags,
            'value' => $this->combine($responses),
            'parameters' => $parameters,
            'requestBody' => $requestBody ?? Generator::UNDEFINED,
        ]);
    }

    private function generateRequestBody(string $requestClass, string $requestType): OA\RequestBody
    {
        $mediaType = $requestType === 'form' ? 'multipart/form-data' : 'application/json';

        return new OA\RequestBody(
            request: $requestClass,
            required: true,
            content: new OA\MediaType(
                mediaType: $mediaType,
                schema: new OA\Schema(ref: '#/components/schemas/' . class_basename($requestClass))
            )
        );
    }

    private function generateValidationErrorResponse(string $requestClass): OA\Response
    {
        $reflectionClass = new \ReflectionClass($requestClass);
        $instance = $reflectionClass->newInstance();
        $rules = $instance->rules();

        $exampleErrors = [];
        foreach ($rules as $field => $rule) {
            $exampleErrors[$field] = ["The $field field is invalid."];
        }

        return new OA\Response(
            response: 422,
            description: 'Validation Error',
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(
                        property: 'success',
                        type: 'bool',
                        example: false
                    ),
                    new OA\Property(
                        property: 'message',
                        type: 'string',
                        example: 'The given data was invalid.'
                    ),
                    new OA\Property(
                        property: 'errors',
                        type: 'object',
                        example: $exampleErrors
                    )
                ]
            )
        );
    }
}
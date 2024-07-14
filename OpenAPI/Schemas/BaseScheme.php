<?php

namespace OpenAPI\Schemas;

use OpenApi\Attributes as OA;
use ReflectionClass;
use ReflectionException;

// Don`t used it, but it's a good
#[\Attribute]
class BaseScheme extends OA\Schema
{
    /**
     * @throws ReflectionException
     */
    public function __construct(
        string $resource,
        array $properties,
        ?array $required = null,
        ?bool $additionalProperties = false
    ) {
        $className = (new ReflectionClass($resource))->getShortName();

        $required ??= array_reduce($properties, function (array $result, OA\Property $property) {
            if ($property->required) {
                $result[] = $property->property;
            }
            return $result;
        }, []);

        parent::__construct(
            schema: $className,
            required: $required,
            properties: $properties,
            additionalProperties: $additionalProperties
        );
    }
}

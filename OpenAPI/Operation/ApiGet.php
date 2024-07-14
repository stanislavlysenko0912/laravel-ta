<?php

namespace OpenAPI\Operation;

use OpenApi\Annotations\Get;
use OpenAPI\Operation\Common\ApiOperation;

#[\Attribute]
class ApiGet extends Get
{
    use ApiOperation;
}

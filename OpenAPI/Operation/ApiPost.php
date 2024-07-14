<?php

namespace OpenAPI\Operation;

use OpenApi\Annotations\Post;
use OpenAPI\Operation\Common\ApiOperation;

#[\Attribute]
class ApiPost extends Post
{
    use ApiOperation;
}

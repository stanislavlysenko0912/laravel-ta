<?php

namespace App\Services\ImageOptimizer\Optimizers;

use Exception;
use GuzzleHttp\Exception\GuzzleException;

interface OptimizerInterface
{
    /**
     * Returning the optimized image data or throw an exception
     *
     * @param string $imageData The image data
     * @return string The optimized image data
     *
     * @throws Exception|GuzzleException
     */
    public function optimize(string $imageData): string;
}
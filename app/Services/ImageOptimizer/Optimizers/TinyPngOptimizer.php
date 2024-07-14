<?php

namespace App\Services\ImageOptimizer\Optimizers;

use GuzzleHttp\Client;

class TinyPngOptimizer implements OptimizerInterface
{
    private const TINYPNG_API = 'https://api.tinify.com/shrink';
    private string $apiKey;

    public function __construct()
    {
        $this->apiKey = config('services.image_optimizer.api_keys.tinypng');
    }

    public function optimize(string $imageData): string
    {
        $client = new Client();

        $response = $client->post(self::TINYPNG_API, [
            'headers' => [
                'Content-Type' => 'application/octet-stream'
            ],
            'auth' => ['api', $this->apiKey],
            'body' => $imageData
        ]);

        $result = json_decode($response->getBody(), true);

        $optimizedImageResponse = $client->get($result['output']['url']);

        return $optimizedImageResponse->getBody()->getContents();
    }
}
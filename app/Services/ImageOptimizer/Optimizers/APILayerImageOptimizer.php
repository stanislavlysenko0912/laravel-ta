<?php

namespace App\Services\ImageOptimizer\Optimizers;

use GuzzleHttp\Client;

/**
 * https://apilayer.com/marketplace/image_optimizer-api
 */
class APILayerImageOptimizer implements OptimizerInterface
{
    private const APILAYER_API = 'https://api.apilayer.com/image_optimizer/upload';
    private string $apiKey;
    private Client $client;

    public function __construct()
    {
        $this->apiKey = config('services.image_optimizer.api_keys.apilayer');
        $this->client = new Client();
    }

    public function optimize(string $imageData): string
    {
        $response = $this->client->post(self::APILAYER_API, [
            'headers' => [
                'apikey' => $this->apiKey,
                'Content-Type' => 'image/jpeg',
            ],
            'body' => $imageData,
        ]);


        $result = json_decode($response->getBody(), true);

        $optimizedImageResponse = $this->client->get($result['optimized_image']);

        return $optimizedImageResponse->getBody()->getContents();
    }
}
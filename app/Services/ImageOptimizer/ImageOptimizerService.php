<?php

namespace App\Services\ImageOptimizer;

use App\Services\ImageOptimizer\Optimizers\OptimizerInterface;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Intervention\Image\ImageManager;


class ImageOptimizerService
{
    /**
     * Optimize image using available services
     *
     * @param string $imageData
     * @return string Optimized image data (if any service succeeded, original image data otherwise)
     */
    public function optimize(string $imageData): string
    {
        $imageManager = ImageManager::gd();

        // Crop in test assignment, but I think it should be resized
        $processedImage = $imageManager->read($imageData)->crop(
            width: 70,
            height: 70,
            position: 'center'
        );

        $base64Image = $processedImage->encode();

        foreach (config('services.image_optimizer.services') as $optimizerClass) {
            $optimizer = app($optimizerClass);

            if (!$optimizer instanceof OptimizerInterface) {
                \Log::error("[ImageOptimizerService] {$optimizerClass} must implement OptimizerInterface");
                continue;
            }

            try {
                return $optimizer->optimize($base64Image);
            } catch (Exception|GuzzleException $e) {
                \Log::error("[ImageOptimizerService] {$optimizerClass} failed with error: {$e->getMessage()}");
                continue;
            }
        }


        return $base64Image;
    }
}
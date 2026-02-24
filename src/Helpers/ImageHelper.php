<?php

namespace CommonMy\LaravelCommon\Helpers;

use Exception;
use Illuminate\Support\Facades\Log;
use Intervention\Image\ImageManager;
use Storage;

class ImageHelper
{
    /**
     * Compress image and convert to base64 data URL for PDF embedding
     *
     * @param string $path
     *
     * @return string|null Base64 data URL or null if compression fails
     */
    public static function compressImageToBase64(string $path): ?string
    {
        try {
            // Create ImageManager instance
            $manager = ImageManager::gd();

            $fileContents = Storage::disk('s3')->get($path);
            // Load image from URL
            $image = $manager->read($fileContents);

            // Resize image while maintaining aspect ratio (max width/height: 300px)
            $image->scale(width: 360, height: 360);

            // Compress and encode to base64 JPEG with 70% quality
            $encodedImage = $image->toJpeg(79, progressive: true);

            // Create data URL
            return 'data:image/jpeg;base64,' . base64_encode($encodedImage);
        } catch (Exception $e) {
            Log::warning('Failed to compress image: ' . $path, ['error' => $e->getMessage()]);

            return null;
        }
    }
}

<?php

namespace App\Components\ImageAnalyzer\Providers;

use App\Components\ImageAnalyzer\IImageCaptioning;
use Cloudinary\Api\Exception\ApiError;
use Cloudinary\Cloudinary;
use Cloudinary\Configuration\Configuration;
use Illuminate\Support\Facades\Log;

class CloudinaryCaptioning implements IImageCaptioning
{
    protected Cloudinary $cloudinary;

    public function __construct(array $config)
    {
        $configuration = Configuration::instance([
            'cloud' => $config,
            'url' => [
                'secure' => true
            ]
        ]);

        $this->cloudinary = new Cloudinary($configuration);
    }

    public function describe(string $imageUrl): string
    {
        try {
            $result = $this->cloudinary->uploadApi()->upload($imageUrl,
                ["detection" => "captioning"])->getArrayCopy();

            return $result['info']['detection']['captioning']['data']['caption'] ?? '';
        } catch (ApiError $error){
            Log::error($error->getMessage());
            return '';
        }
    }
}

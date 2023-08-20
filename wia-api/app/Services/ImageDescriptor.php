<?php

namespace App\Services;

use App\Components\ImageAnalyzer\IImageCaptioning;
use App\Models\Image;
use Illuminate\Support\Facades\Log;

class ImageDescriptor
{
    public function __construct(protected  IImageCaptioning $imageCaptioning){}

    public function describe(string $imageUrl): Image
    {
        $image = app(Image::class);
        $imageId = $image->generateImageId($imageUrl);

        $image = $image->find($imageId);

        if (empty($image)) {
            $image = app(Image::class)->create([
                'id' => $imageId,
                'src' => $imageUrl,
                'description' => $this->imageCaptioning->describe($imageUrl)
            ]);
        }

        return $image;
    }
}

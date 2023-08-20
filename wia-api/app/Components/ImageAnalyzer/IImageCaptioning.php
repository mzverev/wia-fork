<?php

namespace App\Components\ImageAnalyzer;

interface IImageCaptioning
{
    /**
     * Get image descriptions by using visual captioning
     * @param string $imageUrl
     * @return string
     */
    public function describe(string $imageUrl): string;
}

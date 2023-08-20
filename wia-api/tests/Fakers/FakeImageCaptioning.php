<?php

namespace Tests\Fakers;

use App\Components\ImageAnalyzer\IImageCaptioning;

class FakeImageCaptioning implements IImageCaptioning
{
    public function __construct(protected $descriptionLength = 100){}

    public function describe(string $imageUrl): string
    {
        return fake()->text($this->descriptionLength);
    }
}
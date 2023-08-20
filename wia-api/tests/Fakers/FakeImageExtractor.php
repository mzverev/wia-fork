<?php

namespace Tests\Fakers;

use App\Components\WebScraper\Contracts\IExtractor;

class FakeImageExtractor implements IExtractor
{
    public function __construct(protected int $numOfImages){}

    public function extract(): array
    {
        $imageUrls = [];

        for($i = 0; $i < $this->numOfImages; $i++){
            $imageUrls[] = fake()->imageUrl();
        }

        return $imageUrls;
    }
}
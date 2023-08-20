<?php

namespace App\Components\WebScraper\Scrapers\Symfony\Extractors;

use Illuminate\Support\Facades\Log;

class ImageExtractor extends Extractor
{
    protected string $selector = 'img';

    public function extract(): array
    {
        $images = $this->filterElements()->images();

        return array_unique(array_map(function ($image) {
            return $image->getUri();
        }, $this->filter($images)));
    }

    protected function filter(array $images): array
    {
        return array_filter($images, function($image) {
            return !str_starts_with($image->getUri(), 'data:image');
        });
    }
}

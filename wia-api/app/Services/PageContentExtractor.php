<?php

namespace App\Services;

use App\Components\WebScraper\ContentType;
use App\Components\WebScraper\Contracts\IWebScraper;
use Illuminate\Support\Collection;

class PageContentExtractor
{
    protected IWebScraper $webScraper;

    public function __construct(IWebScraper $webScraper)
    {
        $this->webScraper = $webScraper;
    }

    public function extractImageUrls(string $webPageUrl): Collection
    {
        return new Collection($this->webScraper
            ->setUrl($webPageUrl)
            ->setContentType(ContentType::IMAGE)
            ->buildExtractor()
            ->extract());
    }
}

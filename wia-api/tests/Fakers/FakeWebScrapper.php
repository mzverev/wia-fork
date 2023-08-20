<?php

namespace Tests\Fakers;

use App\Components\WebScraper\ContentType;
use App\Components\WebScraper\Contracts\IExtractor;
use App\Components\WebScraper\Contracts\IWebScraper;

class FakeWebScrapper implements IWebScraper
{
    public function __construct(protected int $numOfImages = 4){}

    public function setUrl(string $url): IWebScraper
    {
        return $this;
    }

    public function setContentType(ContentType $contentType): IWebScraper
    {
        return $this;
    }

    public function buildExtractor(): IExtractor
    {
        return new FakeImageExtractor($this->numOfImages);
    }
}
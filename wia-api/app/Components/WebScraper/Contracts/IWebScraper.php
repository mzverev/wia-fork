<?php

namespace App\Components\WebScraper\Contracts;

use App\Components\WebScraper\ContentType;

interface IWebScraper
{
    public function setUrl(string $url): self;
    public function setContentType(ContentType $contentType): self;
    public function buildExtractor(): IExtractor;
}

<?php

namespace App\Components\WebScraper\Scrapers\Symfony;

use App\Components\WebScraper\ContentType;
use App\Components\WebScraper\Contracts\IExtractor;
use App\Components\WebScraper\Contracts\IWebScraper;
use Symfony\Component\BrowserKit\HttpBrowser;
use Symfony\Component\HttpClient\HttpClient;

class SymfonyScraper implements IWebScraper
{
    protected string $url;
    protected ContentType $contentType;

    public function setUrl(string $url): self
    {
        $this->url = $url;
        return $this;
    }

    protected function getUrl(): string
    {
        return $this->url;
    }
    
    public function setContentType(ContentType $contentType): self
    {
        $this->contentType = $contentType;
        return $this;
    }
    
    protected function getContentType(): ContentType
    {
        return $this->contentType;
    }

    public function buildExtractor(): IExtractor
    {
        $extractorName = __NAMESPACE__ . "\\Extractors\\{$this->getContentType()->value}Extractor";

        $crawler = (new HttpBrowser(HttpClient::create()))->request('GET', $this->getUrl());

        $extractor = new $extractorName($this->getUrl());
        $extractor->setCrawler($crawler);

        return $extractor;
    }
}

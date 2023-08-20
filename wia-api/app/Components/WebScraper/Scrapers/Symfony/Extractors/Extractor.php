<?php

namespace App\Components\WebScraper\Scrapers\Symfony\Extractors;

use App\Components\WebScraper\Contracts\IExtractor;
use Symfony\Component\DomCrawler\Crawler;

abstract class Extractor implements IExtractor
{
    protected Crawler $crawler;
    protected string $selector;

    public function setCrawler(Crawler $crawler): self
    {
        $this->crawler = $crawler;
        return $this;
    }

    protected function getCrawler(): Crawler
    {
        return $this->crawler;
    }

    protected function getSelector(): string
    {
        return $this->selector;
    }

    protected function filterElements(): Crawler
    {
        return $this->getCrawler()->filter($this->getSelector());
    }


}

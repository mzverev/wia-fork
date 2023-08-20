<?php

namespace App\Components\WebScraper\Contracts;

interface IExtractor
{
    /**
     * Extract elements from a page
     * @return array
     */
    public function extract(): array;
}

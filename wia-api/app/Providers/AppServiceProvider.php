<?php

namespace App\Providers;

use App\Components\ImageAnalyzer\IImageCaptioning;
use App\Components\ImageAnalyzer\Providers\CloudinaryCaptioning;
use App\Components\WebScraper\Contracts\IWebScraper;
use App\Components\WebScraper\Scrapers\Symfony\SymfonyScraper;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(IWebScraper::class, SymfonyScraper::class);

        $this->app->bind(IImageCaptioning::class, function(Application $app){
            return $app->make(CloudinaryCaptioning::class, ['config' => config('services.cloudinary')]);
        });
    }
}

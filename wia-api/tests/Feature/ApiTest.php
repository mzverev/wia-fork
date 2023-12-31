<?php

namespace Tests\Feature;

use App\Components\ImageAnalyzer\IImageCaptioning;
use App\Components\WebScraper\Contracts\IWebScraper;
use App\Jobs\DescribeImageJob;
use App\Rules\ValidImageUrl;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Queue;
use Mockery\MockInterface;
use Tests\Fakers\FakeImageCaptioning;
use Tests\Fakers\FakeWebScrapper;
use Tests\TestCase;

class ApiTest extends TestCase
{
    protected int $numOfPageImages = 4;

    public function setUp(): void
    {
        parent::setUp();

        app()->bind(IWebScraper::class, function(Application $app){
            return $app->make(FakeWebScrapper::class, ['numOfImages' => $this->numOfPageImages]);
        });

        app()->bind(IImageCaptioning::class, FakeImageCaptioning::class);
    }

    /**
     * @case Verifies the endpoint for captioning an image
     * @endpoint /v1/images/describe
     * @expected
     *  - the endpoint should respond with 1 image
     *  - the image structure should have src and description
     * @return void
     */
    public function test_describe_image(): void
    {
        $this->mockImageValidator();
        $response = $this->postJson("/api/v1/images/describe", ['url' => fake()->imageUrl()]);

        $response->assertJsonCount(1);
        $response->assertJsonStructure([
            '*' => ['src', 'description']
        ]);
    }

    /**
     * @case Verifies the endpoint for captioning the images of a web page
     * @endpoint /v1/webpages/images/describe
     * @expected
     *  - the endpoint response code should be 200
     *  - the image models generated by endpoint should be equal to the expected image models
     * @return void
     */
    public function test_describe_images_of_page()
    {
        Queue::fake();

        $this->postJson("/api/v1/webpages/images/describe", ['url' => fake()->url()]);

        Queue::assertPushed(DescribeImageJob::class, $this->numOfPageImages);
    }

    /**
     * Image validator mocked to improve the tests performance
     * @return MockInterface
     */
    protected function mockImageValidator(): MockInterface
    {
        $imageUrlValidatorMock = $this->mock(ValidImageUrl::class);
        $imageUrlValidatorMock->shouldReceive('validate');

        return $imageUrlValidatorMock;
    }
}

<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\DescribeImageByWebPageRequest;
use App\Http\Requests\DescribeImageRequest;
use App\Http\Resources\ImageResource;
use App\Jobs\DescribeImageJob;
use App\Services\ImageDescriptor;
use App\Services\PageContentExtractor;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;

class ImageController extends Controller
{
    public function __construct(protected ImageDescriptor      $imageDescriptor,
                                protected PageContentExtractor $pageContentExtractor){}

    /**
     * Caption an image
     * @param DescribeImageRequest $request
     * @return ImageResource
     */
    public function describe(DescribeImageRequest $request): ImageResource
    {
        $url = $request->input('url');

        return new ImageResource($this->imageDescriptor->describe($url));
    }

    /**
     * Caption the images of a web page
     * @param DescribeImageByWebPageRequest $request
     * @return JsonResponse
     */
    public function describeByWebPage(DescribeImageByWebPageRequest $request): JsonResponse
    {
        $url = $request->input('url');

        // Extracts images url from the given web page and pass them to the job of captioning process
        $this->pageContentExtractor->extractImageUrls($url)->each(function($imageUrl){
            Log::info($imageUrl);
            app(DescribeImageJob::class, ['imageUrl' => $imageUrl])->dispatch($imageUrl);
        });

        return response()->json(['success' => true]);
    }
}

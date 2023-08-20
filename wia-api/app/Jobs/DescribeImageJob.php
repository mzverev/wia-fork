<?php

namespace App\Jobs;

use App\Events\ImageCaptioningProceed;
use App\Http\Resources\ImageResource;
use App\Services\ImageDescriptor;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class DescribeImageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public string $imageUrl){}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info('here');
        $image = app(ImageDescriptor::class)->describe($this->imageUrl);
        $imageResource = app(ImageResource::class, ['resource' => $image]);

        event(app(ImageCaptioningProceed::class, ['image' => $imageResource]));
    }
}

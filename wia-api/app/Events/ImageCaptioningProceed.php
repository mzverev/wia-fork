<?php

namespace App\Events;

use App\Http\Resources\ImageResource;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ImageCaptioningProceed implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(public ImageResource $image){}

    public function broadcastOn(): array
    {
        return [
            new Channel('image-processing'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'images.described';
    }
}

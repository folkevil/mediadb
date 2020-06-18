<?php

namespace App\Listeners\Media;

use App\Jobs\Media\CreatePreview;
use App\Jobs\Media\SetAttributes;
use App\Jobs\Media\SetProcessed;
use App\Models\Media;
use Spatie\MediaLibrary\MediaCollections\Events\MediaHasBeenAdded;

class Process
{
    /**
     * @var Media
     */
    protected $media;

    /**
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Handle the event.
     *
     * @param object $event
     *
     * @return void
     */
    public function handle(MediaHasBeenAdded $event)
    {
        // e.g. video/mp4 => video
        $type = strtok($event->media->mime_type, '/');

        switch ($type) {
            case 'video':
                SetAttributes::withChain([
                    new CreatePreview($event->media),
                    new SetProcessed($event->media),
                ])->dispatch($event->media);
                break;
        }
    }
}
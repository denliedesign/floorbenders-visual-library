<?php

namespace App\Jobs\Media;

use App\Enums\MediaProcessingStatus;
use App\Models\MovementMediaAsset;
use App\Services\Media\MovementVideoProcessor;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Throwable;

class ProcessMovementVideo implements ShouldQueue
{
    use Queueable;

    public int $tries = 2;

    public int $timeout = 300;

    public function __construct(
        public int $mediaAssetId
    ) {}

    public function handle(MovementVideoProcessor $processor): void
    {
        $mediaAsset = MovementMediaAsset::findOrFail($this->mediaAssetId);

        $processor->process($mediaAsset);
    }

    public function failed(?Throwable $exception): void
    {
        $mediaAsset = MovementMediaAsset::find($this->mediaAssetId);

        if (! $mediaAsset) {
            return;
        }

        $mediaAsset->update([
            'processing_status' => MediaProcessingStatus::Failed,
            'processing_error' => $exception?->getMessage(),
        ]);
    }
}

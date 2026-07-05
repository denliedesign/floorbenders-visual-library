<?php

namespace App\Jobs\Media;

use App\Enums\SequenceMediaProcessingStatus;
use App\Models\Sequence;
use App\Services\Media\SequenceVideoProcessor;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Throwable;

class ProcessSequenceVideo implements ShouldQueue, ShouldBeUnique
{
    use Queueable;

    public int $tries = 2;

    public int $timeout = 900;

    public int $uniqueFor = 900;

    public function __construct(
        public int $sequenceId
    ) {
        //
    }

    public function uniqueId(): string
    {
        return (string) $this->sequenceId;
    }

    public function handle(SequenceVideoProcessor $processor): void
    {
        $sequence = Sequence::query()
            ->with('sequenceMovements.movement.mediaAsset')
            ->findOrFail($this->sequenceId);

        $processor->process($sequence);
    }

    public function failed(Throwable $exception): void
    {
        $sequence = Sequence::query()->find($this->sequenceId);

        if (! $sequence) {
            return;
        }

        $sequence->forceFill([
            'phrase_processing_status' => SequenceMediaProcessingStatus::Failed,
            'phrase_processing_error' => $exception->getMessage(),
        ])->save();
    }
}

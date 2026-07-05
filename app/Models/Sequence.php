<?php

namespace App\Models;

use App\Enums\SequenceMediaProcessingStatus;
use App\Enums\SequenceStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Sequence extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'description',
        'status',
        'featured',
        'phrase_video_path',
        'phrase_gif_path',
        'phrase_thumbnail_path',
        'phrase_processing_status',
        'phrase_processing_error',
        'phrase_processed_at',
        'phrase_media_fingerprint',
    ];

    protected function casts(): array
    {
        return [
            'status' => SequenceStatus::class,
            'featured' => 'boolean',
            'phrase_processing_status' => SequenceMediaProcessingStatus::class,
            'phrase_processed_at' => 'datetime',
        ];
    }

    public function sequenceMovements(): HasMany
    {
        return $this->hasMany(SequenceMovement::class)
            ->orderBy('sort_order');
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', SequenceStatus::Published->value);
    }

    public function phraseVideoUrl(): ?string
    {
        return $this->phrase_video_path
            ? Storage::disk('public')->url($this->phrase_video_path)
            : null;
    }

    public function phraseGifUrl(): ?string
    {
        return $this->phrase_gif_path
            ? Storage::disk('public')->url($this->phrase_gif_path)
            : null;
    }

    public function phraseThumbnailUrl(): ?string
    {
        return $this->phrase_thumbnail_path
            ? Storage::disk('public')->url($this->phrase_thumbnail_path)
            : null;
    }

    public function hasGeneratedPhraseMedia(): bool
    {
        return $this->phrase_processing_status === SequenceMediaProcessingStatus::Complete
            && filled($this->phrase_video_path)
            && filled($this->phrase_gif_path)
            && filled($this->phrase_thumbnail_path)
            && $this->phraseMediaIsCurrent();
    }

    public function deleteGeneratedPhraseMediaFiles(): void
    {
        $disk = Storage::disk('public');

        foreach ([
            $this->phrase_video_path,
            $this->phrase_gif_path,
            $this->phrase_thumbnail_path,
        ] as $path) {
            if ($path && $disk->exists($path)) {
                $disk->delete($path);
            }
        }
    }

    public function clearGeneratedPhraseMedia(): void
    {
        $this->deleteGeneratedPhraseMediaFiles();

        $this->forceFill([
            'phrase_video_path' => null,
            'phrase_gif_path' => null,
            'phrase_thumbnail_path' => null,
            'phrase_processing_status' => SequenceMediaProcessingStatus::Missing,
            'phrase_processing_error' => null,
            'phrase_processed_at' => null,
            'phrase_media_fingerprint' => null,
        ])->save();
    }

    public function markPhraseMediaStale(): void
    {
        if (
            $this->phrase_processing_status === SequenceMediaProcessingStatus::Complete
            || $this->phrase_processing_status === SequenceMediaProcessingStatus::Processing
        ) {
            $this->forceFill([
                'phrase_processing_status' => SequenceMediaProcessingStatus::Stale,
            ])->save();
        }
    }

    public function currentPhraseMediaFingerprint(): ?string
    {
        $this->loadMissing([
            'sequenceMovements' => fn ($query) => $query
                ->with('movement.mediaAsset')
                ->orderBy('sort_order'),
        ]);

        if ($this->sequenceMovements->isEmpty()) {
            return null;
        }

        $payload = $this->sequenceMovements
            ->map(function ($sequenceMovement): array {
                $movement = $sequenceMovement->movement;
                $mediaAsset = $movement?->mediaAsset;

                return [
                    'sequence_movement_id' => $sequenceMovement->id,
                    'movement_id' => $movement?->id,
                    'sort_order' => $sequenceMovement->sort_order,
                    'clean_video_path' => $mediaAsset?->clean_video_path,
                    'media_updated_at' => $mediaAsset?->updated_at?->timestamp,
                ];
            })
            ->values()
            ->toJson();

        return sha1($payload);
    }

    public function phraseMediaIsCurrent(): bool
    {
        $currentFingerprint = $this->currentPhraseMediaFingerprint();

        return filled($currentFingerprint)
            && filled($this->phrase_media_fingerprint)
            && $this->phrase_media_fingerprint === $currentFingerprint;
    }

    public function phraseMediaDisplayLabel(): string
    {
        if ($this->phrase_processing_status === SequenceMediaProcessingStatus::Processing) {
            return 'Processing';
        }

        if ($this->phrase_processing_status === SequenceMediaProcessingStatus::Failed) {
            return 'Failed';
        }

        if ($this->phrase_processing_status === SequenceMediaProcessingStatus::Stale) {
            return 'Outdated';
        }

        if ($this->hasGeneratedPhraseMedia()) {
            return 'Generated Preview';
        }

        if (
            $this->phrase_processing_status === SequenceMediaProcessingStatus::Complete
            && ! $this->phraseMediaIsCurrent()
        ) {
            return 'Outdated';
        }

        return 'No Generated Media';
    }

    public function phraseMediaDisplayBadgeVariant(): string
    {
        if ($this->phrase_processing_status === SequenceMediaProcessingStatus::Processing) {
            return 'amber';
        }

        if ($this->phrase_processing_status === SequenceMediaProcessingStatus::Failed) {
            return 'danger';
        }

        if (
            $this->phrase_processing_status === SequenceMediaProcessingStatus::Stale
            || (
                $this->phrase_processing_status === SequenceMediaProcessingStatus::Complete
                && ! $this->phraseMediaIsCurrent()
            )
        ) {
            return 'amber';
        }

        if ($this->hasGeneratedPhraseMedia()) {
            return 'teal';
        }

        return 'slate';
    }
}

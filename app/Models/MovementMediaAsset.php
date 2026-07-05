<?php

namespace App\Models;

use App\Enums\MediaProcessingStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class MovementMediaAsset extends Model
{
    protected $fillable = [
        'movement_id',
        'raw_video_path',
        'original_filename',
        'mime_type',
        'size_bytes',
        'trim_start_seconds',
        'trim_end_seconds',
        'clean_video_path',
        'gif_path',
        'thumbnail_path',
        'processing_status',
        'processing_error',
        'processed_at',
    ];

    protected function casts(): array
    {
        return [
            'trim_start_seconds' => 'decimal:3',
            'trim_end_seconds' => 'decimal:3',
            'processing_status' => MediaProcessingStatus::class,
            'processed_at' => 'datetime',
        ];
    }

    public function movement(): BelongsTo
    {
        return $this->belongsTo(Movement::class);
    }

    public function rawVideoUrl(): string
    {
        return Storage::disk('public')->url($this->raw_video_path);
    }

    public function cleanVideoUrl(): ?string
    {
        return $this->clean_video_path
            ? Storage::disk('public')->url($this->clean_video_path)
            : null;
    }

    public function gifUrl(): ?string
    {
        return $this->gif_path
            ? Storage::disk('public')->url($this->gif_path)
            : null;
    }

    public function thumbnailUrl(): ?string
    {
        return $this->thumbnail_path
            ? Storage::disk('public')->url($this->thumbnail_path)
            : null;
    }
}

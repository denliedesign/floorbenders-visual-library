<?php

namespace App\Livewire\Admin\Media;

use App\Enums\MediaProcessingStatus;
use App\Jobs\Media\ProcessMovementVideo;
use App\Models\Movement;
use App\Models\MovementMediaAsset;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;

class MovementMediaManager extends Component
{
    use WithFileUploads;

    public Movement $movement;

    public ?MovementMediaAsset $mediaAsset = null;

    public ?TemporaryUploadedFile $rawVideo = null;

    public ?string $trimStartSeconds = null;

    public ?string $trimEndSeconds = null;

    public function mount(Movement $movement): void
    {
        $this->movement = $movement;
        $this->mediaAsset = $movement->mediaAsset;

        if ($this->mediaAsset) {
            $this->trimStartSeconds = $this->mediaAsset->trim_start_seconds;
            $this->trimEndSeconds = $this->mediaAsset->trim_end_seconds;
        }
    }

    public function saveUpload(): void
    {
        $this->validate([
            'rawVideo' => 'required|file|max:512000|mimetypes:video/mp4,video/quicktime,video/webm,video/x-msvideo',
        ]);

        if ($this->mediaAsset?->raw_video_path) {
            Storage::disk('public')->delete($this->mediaAsset->raw_video_path);
        }

        $originalFilename = $this->rawVideo->getClientOriginalName();
        $mimeType = $this->rawVideo->getMimeType();
        $sizeBytes = $this->rawVideo->getSize();

        $path = $this->rawVideo->store(
            "movements/{$this->movement->id}/raw",
            'public'
        );

        $this->mediaAsset = MovementMediaAsset::updateOrCreate(
            ['movement_id' => $this->movement->id],
            [
                'raw_video_path' => $path,
                'original_filename' => $originalFilename,
                'mime_type' => $mimeType,
                'size_bytes' => $sizeBytes,
                'processing_status' => MediaProcessingStatus::Uploaded->value,
                'processing_error' => null,
                'processed_at' => null,
                'clean_video_path' => null,
                'gif_path' => null,
                'thumbnail_path' => null,
            ]
        );

        $this->rawVideo = null;
        $this->trimStartSeconds = $this->mediaAsset->trim_start_seconds;
        $this->trimEndSeconds = $this->mediaAsset->trim_end_seconds;

        session()->flash('status', 'Raw video uploaded.');
    }

    public function saveTrimPoints(): void
    {
        $this->validate([
            'trimStartSeconds' => ['required', 'numeric', 'min:0'],
            'trimEndSeconds' => ['required', 'numeric', 'gt:trimStartSeconds'],
        ]);

        if (! $this->mediaAsset) {
            $this->addError('rawVideo', 'Upload a raw video before setting trim points.');

            return;
        }

        $this->mediaAsset->update([
            'trim_start_seconds' => $this->trimStartSeconds,
            'trim_end_seconds' => $this->trimEndSeconds,
            'processing_status' => MediaProcessingStatus::TrimSet->value,
            'processing_error' => null,
        ]);

        $this->mediaAsset->refresh();

        session()->flash('status', 'Trim points saved.');
    }

    public function removeRawVideo(): void
    {
        if (! $this->mediaAsset) {
            return;
        }

        $paths = array_filter([
            $this->mediaAsset->raw_video_path,
            $this->mediaAsset->clean_video_path,
            $this->mediaAsset->gif_path,
            $this->mediaAsset->thumbnail_path,
        ]);

        if ($paths !== []) {
            Storage::disk('public')->delete($paths);
        }

        $this->mediaAsset->delete();

        $this->mediaAsset = null;
        $this->rawVideo = null;
        $this->trimStartSeconds = null;
        $this->trimEndSeconds = null;

        session()->flash('status', 'Media removed.');
    }

    public function render()
    {
        return view('livewire.admin.media.movement-media-manager')
            ->layout('layouts.public', [
                'title' => "Media for {$this->movement->title}",
            ]);
    }

    public function processMedia(): void
    {
        if (! $this->mediaAsset) {
            $this->addError('rawVideo', 'Upload a raw video before processing.');

            return;
        }

        if ($this->mediaAsset->trim_start_seconds === null || $this->mediaAsset->trim_end_seconds === null) {
            $this->addError('trimStartSeconds', 'Set trim points before processing.');

            return;
        }

        $this->mediaAsset->update([
            'processing_status' => MediaProcessingStatus::Processing,
            'processing_error' => null,
        ]);

        ProcessMovementVideo::dispatch($this->mediaAsset->id);

        $this->mediaAsset->refresh();

        session()->flash('status', 'Media processing started.');
    }
}

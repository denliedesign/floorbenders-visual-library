<?php

namespace App\Services\Media;

use App\Enums\MediaProcessingStatus;
use App\Models\MovementMediaAsset;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class MovementVideoProcessor
{
    public function process(MovementMediaAsset $mediaAsset): MovementMediaAsset
    {
        $mediaAsset->load('movement');

        $this->validateMediaAsset($mediaAsset);

        $mediaAsset->update([
            'processing_status' => MediaProcessingStatus::Processing,
            'processing_error' => null,
            'processed_at' => null,
        ]);

        $disk = Storage::disk('public');

        $movement = $mediaAsset->movement;
        $outputDirectory = "movements/{$movement->id}/processed";

        $disk->makeDirectory($outputDirectory);

        $inputPath = $disk->path($mediaAsset->raw_video_path);

        $cleanVideoPath = "{$outputDirectory}/{$movement->slug}-clean.mp4";
        $gifPath = "{$outputDirectory}/{$movement->slug}-preview.gif";
        $thumbnailPath = "{$outputDirectory}/{$movement->slug}-thumbnail.jpg";
        $palettePath = "{$outputDirectory}/{$movement->slug}-palette.png";

        $cleanVideoAbsolutePath = $disk->path($cleanVideoPath);
        $gifAbsolutePath = $disk->path($gifPath);
        $thumbnailAbsolutePath = $disk->path($thumbnailPath);
        $paletteAbsolutePath = $disk->path($palettePath);

        $trimStart = (float) $mediaAsset->trim_start_seconds;
        $trimEnd = (float) $mediaAsset->trim_end_seconds;
        $duration = $trimEnd - $trimStart;

        try {
            $this->deleteOldOutputs($mediaAsset);

            $this->createCleanVideo(
                inputPath: $inputPath,
                outputPath: $cleanVideoAbsolutePath,
                trimStart: $trimStart,
                duration: $duration
            );

            $this->createThumbnail(
                inputPath: $cleanVideoAbsolutePath,
                outputPath: $thumbnailAbsolutePath
            );

            $this->createGif(
                inputPath: $cleanVideoAbsolutePath,
                palettePath: $paletteAbsolutePath,
                outputPath: $gifAbsolutePath
            );

            $disk->delete($palettePath);

            $mediaAsset->update([
                'clean_video_path' => $cleanVideoPath,
                'gif_path' => $gifPath,
                'thumbnail_path' => $thumbnailPath,
                'processing_status' => MediaProcessingStatus::Complete,
                'processing_error' => null,
                'processed_at' => now(),
            ]);

            return $mediaAsset->fresh();
        } catch (\Throwable $exception) {
            $mediaAsset->update([
                'processing_status' => MediaProcessingStatus::Failed,
                'processing_error' => $exception->getMessage(),
            ]);

            throw $exception;
        }
    }

    protected function validateMediaAsset(MovementMediaAsset $mediaAsset): void
    {
        if (! $mediaAsset->raw_video_path) {
            throw new \RuntimeException('No raw video has been uploaded.');
        }

        if ($mediaAsset->trim_start_seconds === null || $mediaAsset->trim_end_seconds === null) {
            throw new \RuntimeException('Trim start and end points must be set before processing.');
        }

        if ((float) $mediaAsset->trim_end_seconds <= (float) $mediaAsset->trim_start_seconds) {
            throw new \RuntimeException('Trim end must be greater than trim start.');
        }
    }

    protected function createCleanVideo(
        string $inputPath,
        string $outputPath,
        float $trimStart,
        float $duration
    ): void {
        $videoWidth = config('floorbenders.media.video_width', 1280);

        $this->runProcess([
            config('floorbenders.ffmpeg_path'),
            '-y',
            '-ss',
            (string) $trimStart,
            '-i',
            $inputPath,
            '-t',
            (string) $duration,
            '-vf',
            "scale={$videoWidth}:-2",
            '-an',
            '-c:v',
            'libx264',
            '-preset',
            'medium',
            '-crf',
            '23',
            '-pix_fmt',
            'yuv420p',
            $outputPath,
        ]);
    }

    protected function createThumbnail(string $inputPath, string $outputPath): void
    {
        $thumbnailWidth = config('floorbenders.media.thumbnail_width', 640);

        $this->runProcess([
            config('floorbenders.ffmpeg_path'),
            '-y',
            '-i',
            $inputPath,
            '-vf',
            "thumbnail,scale={$thumbnailWidth}:-2",
            '-frames:v',
            '1',
            '-q:v',
            '2',
            $outputPath,
        ]);
    }

    protected function createGif(
        string $inputPath,
        string $palettePath,
        string $outputPath
    ): void {
        $gifWidth = config('floorbenders.media.gif_width', 480);
        $gifFps = config('floorbenders.media.gif_fps', 10);

        $this->runProcess([
            config('floorbenders.ffmpeg_path'),
            '-y',
            '-i',
            $inputPath,
            '-vf',
            "fps={$gifFps},scale={$gifWidth}:-1:flags=lanczos,palettegen",
            $palettePath,
        ]);

        $this->runProcess([
            config('floorbenders.ffmpeg_path'),
            '-y',
            '-i',
            $inputPath,
            '-i',
            $palettePath,
            '-filter_complex',
            "fps={$gifFps},scale={$gifWidth}:-1:flags=lanczos[x];[x][1:v]paletteuse",
            $outputPath,
        ]);
    }

    protected function deleteOldOutputs(MovementMediaAsset $mediaAsset): void
    {
        $paths = array_filter([
            $mediaAsset->clean_video_path,
            $mediaAsset->gif_path,
            $mediaAsset->thumbnail_path,
        ]);

        if ($paths !== []) {
            Storage::disk('public')->delete($paths);
        }
    }

    protected function runProcess(array $command): void
    {
        $process = new Process($command);
        $process->setTimeout(300);
        $process->run();

        if (! $process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }
    }
}

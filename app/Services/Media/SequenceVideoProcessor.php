<?php

namespace App\Services\Media;

use App\Enums\SequenceMediaProcessingStatus;
use App\Models\Sequence;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use RuntimeException;
use Symfony\Component\Process\Process;
use Throwable;

class SequenceVideoProcessor
{
    public function process(Sequence $sequence): Sequence
    {
        $sequence->load([
            'sequenceMovements' => fn ($query) => $query
                ->with('movement.mediaAsset')
                ->orderBy('sort_order'),
        ]);

        $temporaryFiles = [];

        try {
            $this->validateSequence($sequence);

            $sequence->forceFill([
                'phrase_processing_status' => SequenceMediaProcessingStatus::Processing,
                'phrase_processing_error' => null,
            ])->save();

            $sequence->deleteGeneratedPhraseMediaFiles();

            $disk = Storage::disk('public');

            $outputDirectory = "sequences/{$sequence->id}/processed";

            $disk->makeDirectory($outputDirectory);

            $phraseVideoPath = "{$outputDirectory}/phrase.mp4";
            $phraseGifPath = "{$outputDirectory}/phrase.gif";
            $phraseThumbnailPath = "{$outputDirectory}/thumbnail.jpg";

            $inputVideoPaths = $sequence->sequenceMovements
                ->map(fn ($sequenceMovement) => $sequenceMovement->movement->mediaAsset->clean_video_path)
                ->map(fn (string $path) => $disk->path($path))
                ->values()
                ->all();

            $concatListPath = $this->createConcatListFile($inputVideoPaths);
            $temporaryFiles[] = $concatListPath;

            $palettePath = $this->temporaryPath("sequence-{$sequence->id}-palette.png");
            $temporaryFiles[] = $palettePath;

            $this->createPhraseVideo(
                concatListPath: $concatListPath,
                outputPath: $disk->path($phraseVideoPath),
            );

            $this->createPhraseThumbnail(
                inputPath: $disk->path($phraseVideoPath),
                outputPath: $disk->path($phraseThumbnailPath),
            );

            $this->createPhraseGif(
                inputPath: $disk->path($phraseVideoPath),
                palettePath: $palettePath,
                outputPath: $disk->path($phraseGifPath),
            );

            $sequence->forceFill([
                'phrase_video_path' => $phraseVideoPath,
                'phrase_gif_path' => $phraseGifPath,
                'phrase_thumbnail_path' => $phraseThumbnailPath,
                'phrase_processing_status' => SequenceMediaProcessingStatus::Complete,
                'phrase_processing_error' => null,
                'phrase_processed_at' => now(),
                'phrase_media_fingerprint' => $sequence->currentPhraseMediaFingerprint(),
            ])->save();

            return $sequence->fresh();
        } catch (Throwable $exception) {
            $sequence->forceFill([
                'phrase_processing_status' => SequenceMediaProcessingStatus::Failed,
                'phrase_processing_error' => $exception->getMessage(),
            ])->save();

            throw $exception;
        } finally {
            foreach ($temporaryFiles as $temporaryFile) {
                if ($temporaryFile && file_exists($temporaryFile)) {
                    @unlink($temporaryFile);
                }
            }
        }
    }

    protected function validateSequence(Sequence $sequence): void
    {
        if ($sequence->sequenceMovements->isEmpty()) {
            throw new RuntimeException('This phrase has no movement steps.');
        }

        foreach ($sequence->sequenceMovements as $sequenceMovement) {
            $movement = $sequenceMovement->movement;
            $mediaAsset = $movement?->mediaAsset;

            if (! $movement) {
                throw new RuntimeException('A phrase step is missing its movement record.');
            }

            if (! $mediaAsset) {
                throw new RuntimeException("Movement [{$movement->title}] does not have media.");
            }

            if (! $mediaAsset->clean_video_path) {
                throw new RuntimeException("Movement [{$movement->title}] does not have a clean processed MP4.");
            }

            if (! Storage::disk('public')->exists($mediaAsset->clean_video_path)) {
                throw new RuntimeException("Clean MP4 file is missing for movement [{$movement->title}].");
            }
        }
    }

    protected function createConcatListFile(array $inputVideoPaths): string
    {
        $temporaryPath = $this->temporaryPath('sequence-concat-' . uniqid() . '.txt');

        $lines = collect($inputVideoPaths)
            ->map(fn (string $path) => "file '" . $this->escapeConcatPath($path) . "'")
            ->implode(PHP_EOL);

        file_put_contents($temporaryPath, $lines . PHP_EOL);

        return $temporaryPath;
    }

    protected function createPhraseVideo(string $concatListPath, string $outputPath): void
    {
        $videoWidth = (int) config('floorbenders.media.phrase_video_width', 1280);

        $this->runProcess([
            config('floorbenders.ffmpeg_path', 'ffmpeg'),
            '-y',
            '-f',
            'concat',
            '-safe',
            '0',
            '-i',
            $concatListPath,
            '-fflags',
            '+genpts',
            '-vf',
            "scale={$videoWidth}:-2,format=yuv420p",
            '-an',
            '-c:v',
            'libx264',
            '-preset',
            'medium',
            '-crf',
            '23',
            '-movflags',
            '+faststart',
            $outputPath,
        ]);
    }

    protected function createPhraseThumbnail(string $inputPath, string $outputPath): void
    {
        $thumbnailWidth = (int) config('floorbenders.media.phrase_thumbnail_width', 640);

        $this->runProcess([
            config('floorbenders.ffmpeg_path', 'ffmpeg'),
            '-y',
            '-ss',
            '0.5',
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

    protected function createPhraseGif(string $inputPath, string $palettePath, string $outputPath): void
    {
        $gifWidth = (int) config('floorbenders.media.phrase_gif_width', 480);
        $gifFps = (int) config('floorbenders.media.phrase_gif_fps', 8);

        $paletteFilter = "fps={$gifFps},scale={$gifWidth}:-1:flags=lanczos,palettegen";
        $gifFilter = "fps={$gifFps},scale={$gifWidth}:-1:flags=lanczos[x];[x][1:v]paletteuse";

        $this->runProcess([
            config('floorbenders.ffmpeg_path', 'ffmpeg'),
            '-y',
            '-i',
            $inputPath,
            '-vf',
            $paletteFilter,
            $palettePath,
        ]);

        $this->runProcess([
            config('floorbenders.ffmpeg_path', 'ffmpeg'),
            '-y',
            '-i',
            $inputPath,
            '-i',
            $palettePath,
            '-filter_complex',
            $gifFilter,
            '-loop',
            '0',
            $outputPath,
        ]);
    }

    protected function runProcess(array $command): void
    {
        $process = new Process($command);
        $process->setTimeout(600);
        $process->run();

        if (! $process->isSuccessful()) {
            throw new RuntimeException(trim($process->getErrorOutput()) ?: 'FFmpeg process failed.');
        }
    }

    protected function temporaryPath(string $filename): string
    {
        $directory = storage_path('app/temp');

        File::ensureDirectoryExists($directory);

        return $directory . DIRECTORY_SEPARATOR . $filename;
    }

    protected function escapeConcatPath(string $path): string
    {
        return str_replace("'", "'\\''", str_replace('\\', '/', $path));
    }
}

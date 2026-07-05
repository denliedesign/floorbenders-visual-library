<?php

use App\Livewire\Admin\Movements\MovementEdit;
use App\Livewire\Admin\Movements\MovementIndex;
use App\Livewire\Admin\Media\MovementMediaManager;
use App\Livewire\Admin\Sequences\SequenceBrowser;
use App\Livewire\Admin\Sequences\SequenceCreate;
use App\Livewire\Admin\Sequences\SequenceEdit;
use App\Livewire\Admin\Sequences\SequenceIndex as AdminSequenceIndex;
use App\Livewire\Sequences\SequenceIndex as PublicSequenceIndex;
use App\Livewire\Sequences\SequenceShow;
use App\Livewire\Library\MovementBrowser;
use App\Livewire\Library\MovementShow;
use App\Enums\MediaProcessingStatus;
use App\Enums\MovementStatus;
use App\Enums\SequenceStatus;
use App\Enums\SequenceMediaProcessingStatus;
use App\Models\Movement;
use App\Models\MovementMediaAsset;
use App\Models\Sequence;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $totalMovementSlots = \App\Models\Movement::query()->count();

    $publicReadyMovements = \App\Models\Movement::query()
        ->published()
        ->withCompletedMedia()
        ->count();

    $processedMediaAssets = \App\Models\MovementMediaAsset::query()
        ->where('processing_status', \App\Enums\MediaProcessingStatus::Complete)
        ->count();

    $publishedPhrases = \App\Models\Sequence::query()
        ->where('status', \App\Enums\SequenceStatus::Published)
        ->count();

    $featuredPhrase = \App\Models\Sequence::query()
        ->withCount([
            'sequenceMovements' => fn ($query) => $query
                ->with('movement.mediaAsset')
                ->orderBy('sort_order'),
        ])
        ->where('status', \App\Enums\SequenceStatus::Published)
        ->where('featured', true)
        ->orderByDesc('featured')
        ->latest('updated_at')
        ->first();

    $atlasProgressPercent = $totalMovementSlots > 0
        ? round(($publicReadyMovements / $totalMovementSlots) * 100)
        : 0;

    return view('welcome', [
        'totalMovementSlots' => $totalMovementSlots,
        'publicReadyMovements' => $publicReadyMovements,
        'processedMediaAssets' => $processedMediaAssets,
        'publishedPhrases' => $publishedPhrases,
        'featuredPhrase' => $featuredPhrase,
        'atlasProgressPercent' => $atlasProgressPercent,
    ]);
})->name('home');

Route::get('/library', MovementBrowser::class)->name('library.index');

Route::get('/library/{movement:slug}', MovementShow::class)->name('library.show');

Route::get('/sequences', PublicSequenceIndex::class)->name('sequences.index');

Route::get('/sequences/{sequence:slug}', SequenceShow::class)->name('sequences.show');

Route::prefix('taxonomy')->name('taxonomy.')->group(function (): void {
    Route::view('/gates', 'taxonomy.gates')->name('gates');
    Route::view('/aspects', 'taxonomy.aspects')->name('aspects');
    Route::view('/realms', 'taxonomy.realms')->name('realms');
    Route::view('/orientations', 'taxonomy.orientations')->name('orientations');
    Route::view('/layers', 'taxonomy.layers')->name('layers');
    Route::view('/notes', 'taxonomy.notes')->name('notes');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
});

Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', function () {
            $totalMovementSlots = Movement::query()->count();

            $publishedMovements = Movement::query()
                ->where('status', MovementStatus::Published)
                ->count();

            $publicReadyMovements = Movement::query()
                ->published()
                ->withCompletedMedia()
                ->count();

            $mediaAssets = MovementMediaAsset::query()->count();

            $uploadedMediaAssets = MovementMediaAsset::query()
                ->whereNotNull('raw_video_path')
                ->count();

            $processedMediaAssets = MovementMediaAsset::query()
                ->where('processing_status', MediaProcessingStatus::Complete)
                ->count();

            $failedMediaAssets = MovementMediaAsset::query()
                ->where('processing_status', MediaProcessingStatus::Failed)
                ->count();

            $phraseCount = Sequence::query()->count();

            $publishedPhrases = Sequence::query()
                ->where('status', SequenceStatus::Published)
                ->count();

            $featuredPhrases = Sequence::query()
                ->where('featured', true)
                ->count();

            $atlasProgressPercent = $totalMovementSlots > 0
                ? round(($publicReadyMovements / $totalMovementSlots) * 100)
                : 0;

            $mediaProgressPercent = $totalMovementSlots > 0
                ? round(($processedMediaAssets / $totalMovementSlots) * 100)
                : 0;

            $recentMovements = Movement::query()
                ->with('mediaAsset')
                ->latest('updated_at')
                ->take(5)
                ->get();

            $recentPhrases = Sequence::query()
                ->withCount('sequenceMovements')
                ->latest('updated_at')
                ->take(5)
                ->get();

            $sequencesForMedia = Sequence::query()
                ->with([
                    'sequenceMovements' => fn ($query) => $query
                        ->with('movement.mediaAsset')
                        ->orderBy('sort_order'),
                ])
                ->get();

            $generatedPhraseMediaCount = $sequencesForMedia
                ->filter
                ->hasGeneratedPhraseMedia()
                ->count();

            $outdatedPhraseMediaCount = $sequencesForMedia
                ->filter(function (Sequence $sequence): bool {
                    return $sequence->phrase_processing_status === SequenceMediaProcessingStatus::Stale
                        || (
                            $sequence->phrase_processing_status === SequenceMediaProcessingStatus::Complete
                            && ! $sequence->phraseMediaIsCurrent()
                        );
                })
                ->count();

            $failedPhraseMediaCount = $sequencesForMedia
                ->where('phrase_processing_status', SequenceMediaProcessingStatus::Failed)
                ->count();

            $processingPhraseMediaCount = $sequencesForMedia
                ->where('phrase_processing_status', SequenceMediaProcessingStatus::Processing)
                ->count();

            return view('admin.dashboard', [
                'totalMovementSlots' => $totalMovementSlots,
                'publishedMovements' => $publishedMovements,
                'publicReadyMovements' => $publicReadyMovements,
                'mediaAssets' => $mediaAssets,
                'uploadedMediaAssets' => $uploadedMediaAssets,
                'processedMediaAssets' => $processedMediaAssets,
                'failedMediaAssets' => $failedMediaAssets,
                'phraseCount' => $phraseCount,
                'publishedPhrases' => $publishedPhrases,
                'featuredPhrases' => $featuredPhrases,
                'atlasProgressPercent' => $atlasProgressPercent,
                'mediaProgressPercent' => $mediaProgressPercent,
                'recentMovements' => $recentMovements,
                'recentPhrases' => $recentPhrases,
                'generatedPhraseMediaCount' => $generatedPhraseMediaCount,
                'outdatedPhraseMediaCount' => $outdatedPhraseMediaCount,
                'failedPhraseMediaCount' => $failedPhraseMediaCount,
                'processingPhraseMediaCount' => $processingPhraseMediaCount,
            ]);
        })->name('dashboard');

        Route::get('/movements', MovementIndex::class)->name('movements.index');
        Route::get('/movements/{movement}/edit', MovementEdit::class)->name('movements.edit');
        Route::get('/movements/{movement}/media', MovementMediaManager::class)
            ->name('movements.media');

        Route::get('/sequences', AdminSequenceIndex::class)->name('sequences.index');
        Route::get('/sequences/create', SequenceCreate::class)->name('sequences.create');
        Route::get('/sequences/{sequence}/edit', SequenceEdit::class)->name('sequences.edit');
    });

Route::get('/movements', function () {
    return 'Movement admin coming in Phase 3.';
})->name('movements.index');

require __DIR__.'/settings.php';

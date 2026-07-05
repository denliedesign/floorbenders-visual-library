<?php

namespace App\Models;

use App\Enums\Aspect;
use App\Enums\Difficulty;
use App\Enums\FacingDirection;
use App\Enums\Gate;
use App\Enums\MovementStatus;
use App\Enums\Realm;
use App\Enums\MediaProcessingStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Movement extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'gate',
        'aspect',
        'realm',
        'start_position',
        'end_position',
        'start_facing',
        'end_facing',
        'difficulty',
        'status',
        'description',
        'teaching_notes',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'gate' => Gate::class,
            'aspect' => Aspect::class,
            'realm' => Realm::class,
            'start_facing' => FacingDirection::class,
            'end_facing' => FacingDirection::class,
            'difficulty' => Difficulty::class,
            'status' => MovementStatus::class,
        ];
    }

    public function getDisplayNameAttribute(): string
    {
        return "{$this->gate->label()} {$this->aspect->label()} {$this->realm->label()}";
    }

    public function mediaAsset(): HasOne
    {
        return $this->hasOne(MovementMediaAsset::class);
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', \App\Enums\MovementStatus::Published->value);
    }

    public function scopeWithCompletedMedia(Builder $query): Builder
    {
        return $query->whereHas('mediaAsset', function (Builder $query) {
            $query
                ->where('processing_status', MediaProcessingStatus::Complete->value)
                ->whereNotNull('clean_video_path')
                ->whereNotNull('gif_path')
                ->whereNotNull('thumbnail_path');
        });
    }

    public function atlasNote(): string
    {
        return match ($this->realm) {
            Realm::Insect => $this->aspect === Aspect::Sky ? 'do' : 'di',
            Realm::Reptile => $this->aspect === Aspect::Sky ? 're' : 'ri',
            Realm::Mammal => $this->aspect === Aspect::Sky ? 'mi' : 'my',
            Realm::Amphibian => $this->aspect === Aspect::Sky ? 'fa' : 'fi',
            Realm::Bird => $this->aspect === Aspect::Sky ? 'so' : 'si',
            Realm::Fish => $this->aspect === Aspect::Sky ? 'la' : 'li',
        };
    }

    public function atlasNoteLabel(): string
    {
        return strtoupper($this->atlasNote());
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SequenceMovement extends Model
{
    protected $fillable = [
        'sequence_id',
        'movement_id',
        'sort_order',
    ];

    public function sequence(): BelongsTo
    {
        return $this->belongsTo(Sequence::class);
    }

    public function movement(): BelongsTo
    {
        return $this->belongsTo(Movement::class);
    }
}

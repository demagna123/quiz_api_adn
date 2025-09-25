<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LevelTheme extends Model
{
    protected $fillable = [
        'level_id',
        'name',
        'description',
    ];

    public function level(): BelongsTo
    {
        return $this->belongsTo(Level::class);
    }
}

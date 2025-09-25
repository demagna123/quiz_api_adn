<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Level extends Model
{
     protected $fillable = [
        'name',
    ];

    public function levelThemes() : HasMany
    {
        return $this->hasMany(LevelTheme::class);
    }
}

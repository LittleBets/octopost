<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Znck\Eloquent\Traits\BelongsToThrough;

class CompositionResultChoice extends BaseModel
{
    use HasFactory;
    use BelongsToThrough;

    protected $casts = ['extras' => 'array'];

    public function composition() {
        return $this->belongsToThrough(Composition::class, CompositionResult::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class CompositionResult extends BaseModel
{
    use HasFactory;

    protected $casts = ['usage' => 'array'];

    public function composition()
    {
        return $this->belongsTo(Composition::class);
    }

    public function choices()
    {
        return $this->hasMany(CompositionResultChoice::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Composition extends BaseModel
{
    use HasFactory;

    protected $casts = ['payload' => 'array'];

    public function composer()
    {
        return $this->belongsTo(User::class);
    }

    public function parentComposition()
    {
        return $this->belongsTo(Composition::class, 'parent_composition_id');
    }

    public function results()
    {
        return $this->hasMany(CompositionResult::class);
    }
}

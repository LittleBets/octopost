<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Composition extends BaseModel
{
    use HasFactory;

    protected $casts = ['payload' => 'array'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function rootComposition()
    {
        return $this->belongsTo(Composition::class, 'root_composition_id');
    }

    public function result()
    {
        return $this->hasOne(CompositionResult::class);
    }

    public function childrenCompositions() {
        return $this->hasMany(Composition::class, 'root_composition_id');
    }
}

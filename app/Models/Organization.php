<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organization extends BaseModel
{
    use HasFactory;

    public function usage() {
        return $this->hasMany(Usage::class);
    }
}

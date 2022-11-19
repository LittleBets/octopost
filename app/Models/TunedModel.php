<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TunedModel extends BaseModel
{
    use HasFactory;
    protected $casts = ['extras' => 'array'];
}

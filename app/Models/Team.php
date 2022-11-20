<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Jetstream\Events\TeamCreated;
use Laravel\Jetstream\Events\TeamDeleted;
use Laravel\Jetstream\Events\TeamUpdated;
use Laravel\Jetstream\Team as JetstreamTeam;

class Team extends JetstreamTeam
{
    use HasFactory;
    use HasUuids;

    protected $keyType = 'string';

    protected $casts = ['personal_team' => 'boolean',];

    protected $fillable = ['name', 'personal_team',];

    protected $dispatchesEvents = [
        'created' => TeamCreated::class,
        'updated' => TeamUpdated::class,
        'deleted' => TeamDeleted::class,
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function usage() {
        return $this->hasMany(Usage::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Laravel\Jetstream\Jetstream;
use Laravel\Jetstream\TeamInvitation as JetstreamTeamInvitation;

class TeamInvitation extends JetstreamTeamInvitation
{
    use HasUuids;

    protected $fillable = ['email', 'role',];

    public function team()
    {
        return $this->belongsTo(Jetstream::teamModel());
    }
}

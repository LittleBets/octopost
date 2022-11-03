<?php

namespace App\Models;

use Laravel\Jetstream\Jetstream;
use Laravel\Jetstream\TeamInvitation as JetstreamTeamInvitation;

class TeamInvitation extends JetstreamTeamInvitation
{

    protected $keyType = 'string';

    protected $fillable = ['email', 'role',];

    public function team()
    {
        return $this->belongsTo(Jetstream::teamModel());
    }

}

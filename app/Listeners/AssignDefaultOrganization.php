<?php

namespace App\Listeners;

use App\Models\Organization;
use App\Models\TeamInvitation;
use Illuminate\Auth\Events\Registered;

class AssignDefaultOrganization
{
    public function __construct()
    {
    }

    public function handle(Registered $event)
    {
        $invitation = TeamInvitation::where('email', $event->user->email)
            ->with(['team', 'team.organization'])
            ->first();

        if ($invitation) {
            $event->user->organization_id = $invitation->team->organization_id;
            $event->user->save();
        } else {
            // this is a new user so assign them to the default organization
            $faker = \Faker\Factory::create();
            $organization = Organization::create([
                'name' => $faker->company(),
                'owner_id' => $event->user->id,
            ]);
            $event->user->organization_id = $organization->id;
            $event->user->save();
        }
    }
}

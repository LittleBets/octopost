<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;

class Usage extends BaseModel
{
    use HasFactory;

    protected $casts = ['extras' => 'array'];

    public function usageable()
    {
        return $this->morphTo();
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function scopeTeamUsageByInterval(Builder $query, $interval = 'month')
    {
        return $query
            ->select('team_id', 'organization_id')
            ->whereRaw("created_at >= date_trunc(?, CURRENT_DATE)", [$interval])
            ->groupBy('team_id', 'organization_id')
            ->selectRaw("sum(prompt_credits) as total_prompt_credits")
            ->selectRaw("sum(completion_credits) as total_completion_credits")
            ->selectRaw("sum(usages.prompt_credits + usages.completion_credits) AS total_credits");
    }
}

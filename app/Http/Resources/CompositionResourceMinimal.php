<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CompositionResourceMinimal extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'template' => $this->template,
            'label' => $this->label,
            'created_at' => userTimeZone($this->created_at),
            'created_at_short' => shortDateAndTime($this->created_at),
            'versions' => $this->versions,
            'user' => $this->whenLoaded('user', function () {
                return [
                    'id' => $this->user->id,
                    'name' => $this->user->name,
                ];
            }),
        ];
    }
}

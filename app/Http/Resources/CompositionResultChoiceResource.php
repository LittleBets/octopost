<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CompositionResultChoiceResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'text' => $this->text,
            'created_at' => userTimeZone($this->created_at),
            'created_at_short' => shortDateAndTime($this->created_at),
        ];
    }
}

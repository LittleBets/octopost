<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CompositionResultResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'choices' => CompositionResultChoiceResource::collection($this->whenLoaded('choices')),
            'usage' => $this->usage,
        ];
    }
}

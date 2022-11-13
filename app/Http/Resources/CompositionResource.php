<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CompositionResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'template' => $this->template,
            'label' => $this->label,
            'created_at' => userTimeZone($this->created_at),
            'created_at_short' => shortDateAndTime($this->created_at),
            'children' => CompositionResource::collection($this->whenLoaded('childrenCompositions')),
            'composition_result'  => new CompositionResultResource($this->whenLoaded('result')),
            'payload' => $this->prunePayload($this->payload)
        ];
    }

    private function prunePayload(?array $payload)
    {
        if($payload == null) return [];
        $actualPayload = $payload;
        unset($actualPayload['max_tokens'], $actualPayload['model'], $actualPayload['n'], $actualPayload['prompt'], $actualPayload['temperature']);
        return $actualPayload;
    }
}

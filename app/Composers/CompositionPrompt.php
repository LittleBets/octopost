<?php

namespace App\Composers;

class CompositionPrompt
{
    private function __construct(
        public readonly string $model,
        public readonly string $prompt,
        public readonly int    $maxTokens,
        public readonly float  $temperature,
        public readonly int    $n,
    )
    {
    }

    public static function from(array $attributes): self
    {
        return new self(
            model: $attributes['model'],
            prompt: $attributes['prompt'],
            maxTokens: $attributes['max_tokens'],
            temperature: $attributes['temperature'],
            n: $attributes['n'],
        );
    }

    public function toArray(): array
    {
        return [
            'model' => $this->model,
            'prompt' => $this->prompt,
            'max_tokens' => $this->maxTokens,
            'temperature' => $this->temperature,
            'n' => $this->n,
        ];
    }
}

<?php

namespace App\Composers;


use Illuminate\Support\Str;

class FreeformComposer extends BaseComposer
{
    public function prompt(array $payload): CompositionPrompt
    {
        $maxTokens = $payload['composition_length'];

        return CompositionPrompt::from([
            'model' => $payload['model'] ?? 'text-davinci-002',
            'prompt' => $payload['prompt'] ?? '',
            'max_tokens' => $maxTokens,
            'temperature' => 0.9,
            'n' => $payload['variations'],
        ]);
    }

    protected function compositionLabel(array $payload): string
    {
        return Str::substr($payload['prompt'], 0, 50);
    }
}

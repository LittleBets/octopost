<?php

namespace App\Composers;


use Illuminate\Support\Str;

class FreeformComposer extends BaseComposer
{
    public function prompt(array $payload): CompositionPrompt
    {
        $maxWords = $payload['composition_length'];

        return CompositionPrompt::from([
            'model' => $payload['model'] ?? 'gpt-3.5-turbo',
            'prompt' => $payload['input_prompt'] ?? '',
            'max_tokens' => $this->convertWordCreditsToTokensLength($maxWords),
            'temperature' => 0.7,
            'n' => $payload['variations'],
        ]);
    }

    protected function compositionLabel(array $payload): string
    {
        return Str::substr($payload['input_prompt'], 0, 20) . '...';
    }
}

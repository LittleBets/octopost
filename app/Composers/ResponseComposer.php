<?php

namespace App\Composers;

use Illuminate\Support\Str;

class ResponseComposer extends BaseComposer
{
    public function prompt(array $payload): CompositionPrompt
    {
        $maxTokens = match ($payload['composition_length']) {
            'medium' => 160,
            'long' => 240,
            default => 100
        };

        $audience = $payload['audience'] ?? null;
        $addressingLiteral = $audience ? " for {$payload['audience']}" : '';
        $tone = $payload['tone'] ?? '';

        return CompositionPrompt::from([
            'model' => $payload['model'] ?? 'text-davinci-002',
            'prompt' => sprintf("Write a %s reply %s in response to %s:\n\n%s:\n%s", $tone, $addressingLiteral, $payload['response_type'], $payload['response_type'], trim($payload['message'])),
            'max_tokens' => $maxTokens,
            'temperature' => 0.9,
            'n' => $payload['variations'],
        ]);
    }

    protected function compositionLabel(array $payload): string
    {
        return Str::substr($payload['message'], 0, 15);
    }
}

// write a silly reply addressing boss in response to this tweet:
// tweet: "I'm so excited to be here!"

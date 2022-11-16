<?php

namespace App\Composers;

use Illuminate\Support\Str;

class ResponseComposer extends BaseComposer
{
    public function prompt(array $payload): CompositionPrompt
    {
        $audience = $payload['audience'] ?? null;
        $addressingLiteral = $audience ? " for {$payload['audience']}" : '';
        $tone = $payload['tone'] ?? '';

        return CompositionPrompt::from([
            'model' => $payload['model'] ?? 'text-davinci-002',
            'prompt' => sprintf("Write a %s reply %s in response to %s:\n%s:\n%s\nresponse:", $tone, $addressingLiteral, $payload['response_type'], $payload['response_type'], trim($payload['message'])),
            'max_tokens' => $this->compositionLengthToTokens($payload['composition_length']),
            'temperature' => 0.7,
            'n' => $payload['variations'],
        ]);
    }

    protected function compositionLabel(array $payload): string
    {
        return Str::substr($payload['message'], 0, 15);
    }
}

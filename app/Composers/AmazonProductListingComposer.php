<?php

namespace App\Composers;

class AmazonProductListingComposer extends BaseComposer
{
    public function prompt(array $payload): CompositionPrompt
    {
        $maxTokens = match ($payload['composition_length']) {
            'medium' => 160,
            'long' => 240,
            default => 100
        };

        $audience = $payload['audience'] ?? null;
        $targetingLiteral = $audience ? " targeting {$payload['audience']}" : '';
        $tone = $payload['tone'] ?? '';

        return CompositionPrompt::from([
            'model' => $payload['model'] ?? 'text-davinci-002',
            'prompt' => sprintf("Write a %s Amazon listing %s for product and features: \n\nProduct: %s\n\nFeatures: %s", $tone, $targetingLiteral, $payload['name'], $payload['features']),
            'max_tokens' => $maxTokens,
            'temperature' => 0.9,
            'n' => $payload['variations'],
        ]);
    }
}

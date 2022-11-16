<?php

namespace App\Composers;

class AmazonProductListingComposer extends BaseComposer
{
    public function prompt(array $payload): CompositionPrompt
    {
        $audience = $payload['audience'] ?? null;
        $targetingLiteral = $audience ? " targeting {$payload['audience']}" : '';
        $tone = $payload['tone'] ?? '';

        return CompositionPrompt::from([
            'model' => $payload['model'] ?? 'text-davinci-002',
            'prompt' => sprintf("Write a %s Amazon listing %s for product and features: \n\nProduct: %s\nFeatures: %s\nDescription:", $tone, $targetingLiteral, $payload['name'], $payload['features']),
            'max_tokens' => $this->compositionLengthToTokens($payload['composition_length']),
            'temperature' => 0.7,
            'n' => $payload['variations'],
        ]);
    }
}

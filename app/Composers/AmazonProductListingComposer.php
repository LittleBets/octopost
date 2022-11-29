<?php

namespace App\Composers;

class AmazonProductListingComposer extends BaseComposer
{
    public function prompt(array $payload): CompositionPrompt
    {
        $audience = $payload['audience'] ?? null;
        $targetingLiteral = $audience ? " for {$audience}" : '';
        $tone = $payload['tone'] ?? '';

        return CompositionPrompt::from([
            'model' => $payload['model'] ?? 'text-davinci-003',
            'prompt' => sprintf("Write a %s Amazon listing%s:%sProduct: %s%sFeatures: %s%s", $tone, $targetingLiteral, PHP_EOL, trim($payload['name']), PHP_EOL, $payload['features'], config('consts.response_separator')),
            'max_tokens' => $this->compositionLengthToTokens($payload['composition_length']),
            'temperature' => 0.7,
            'n' => $payload['variations'],
        ]);
    }
}

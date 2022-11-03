<?php

namespace App\Composers;

class AmazonProductListingComposer extends BaseComposer
{
    public function prompt(array $payload): CompositionPrompt
    {
        return CompositionPrompt::from([
            'model' => $payload['model'] ?? 'text-davinci-002',
            'prompt' => sprintf("Write a %s listing for the following Amazon product and features for %s: \n\nProduct: %s\n\nFeatures: %s", $payload['tone'], $payload['audience'], $payload['name'], $payload['features']),
            'max_tokens' => 120,
            'temperature' => 0.9,
            'n' => $payload['variations'],
        ]);
    }
}

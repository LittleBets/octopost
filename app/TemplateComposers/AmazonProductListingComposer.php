<?php

namespace App\TemplateComposers;

class AmazonProductListingComposer implements ComposerContract
{
    public function compose(array $payload): array
    {
        return [
            'model' => 'text-davinci-002',
            'prompt' => sprintf("Write a %s listing for the following Amazon product and features for %s: \n\nProduct:%s\n\nFeatures:%s", $payload['tone'], $payload['audience'], $payload['name'], $payload['features']),
            'max_tokens' => 120,
            'temperature' => 0.9,
            'n' => $payload['variations'],
        ];
    }
}

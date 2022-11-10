<?php

namespace App\Composers;

use InvalidArgumentException;
use OpenAI\Client as OpenAIClient;

class ComposerFactory
{
    public function __construct(protected OpenAIClient $client)
    {
    }

    public function make(string $template): ComposerContract
    {
        return match ($template) {
            'amazon-product-listing' => new AmazonProductListingComposer($this->client, $template),
            'freeform' => new FreeformComposer($this->client, $template),
            default => throw new InvalidArgumentException(sprintf('Invalid template %s', $template)),
        };
    }
}

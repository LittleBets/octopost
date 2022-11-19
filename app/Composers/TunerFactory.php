<?php

namespace App\Composers;

use App\Tuners\ResponseTuner;
use App\Tuners\TunerContract;
use InvalidArgumentException;
use OpenAI\Client as OpenAIClient;

class TunerFactory
{
    public function __construct(protected OpenAIClient $client)
    {
    }

    public function make(string $template): TunerContract
    {
        return match ($template) {
//            'amazon-product-listing' => new AmazonProductListingComposer($this->client, $template),
//            'freeform' => new FreeformComposer($this->client, $template),
            'response' => new ResponseTuner($this->client, $template),
            default => throw new InvalidArgumentException(sprintf('Invalid template %s', $template)),
        };
    }
}

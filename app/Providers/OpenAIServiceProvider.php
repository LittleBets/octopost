<?php

namespace App\Providers;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;
use OpenAI;
use OpenAI\Client as OpenAIClient;

class OpenAIServiceProvider extends ServiceProvider implements DeferrableProvider
{
    public function register()
    {
        $this->app->singleton(OpenAIClient::class, function ($app) {
            return OpenAI::client(config('services.openai.key'));
        });
    }

    public function provides(): array
    {
        return [OpenAIClient::class];
    }
}

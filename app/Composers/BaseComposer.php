<?php

namespace App\Composers;

use App\Models\Composition;
use App\Models\CompositionResult;
use App\Models\Usage;
use Faker\Factory;
use OpenAI\Client as OpenAIClient;
use OpenAI\Responses\Completions\CreateResponse;

abstract class BaseComposer implements ComposerContract
{
    protected bool $doFake = false;

    function __construct(protected OpenAIClient $client, protected string $template)
    {
    }

    abstract public function prompt(array $payload): CompositionPrompt;

    protected function compositionLabel(array $payload): string
    {
        return $payload['label'] ?? $payload['name'] ?? 'Untitled';
    }

    public function compose(array $payload, ?string $rootCompositionId = null): array
    {
        $prompt = $this->prompt($payload);
        $promptResponse = $this->complete($prompt);
        $composition = $this->savePrompt($prompt, $payload, $rootCompositionId);
        $compositionResult = $this->saveResponse($promptResponse, $composition);
        return [
            'composition' => $composition,
            'result' => $compositionResult,
        ];
    }

    protected function complete(CompositionPrompt $prompt): CreateResponse
    {
        if ($this->doFake) {
            return $this->fakeResponse($prompt);
        } else {
            return $this->client->completions()->create($prompt->toArray());
        }
    }

    private function savePrompt(CompositionPrompt $prompt, array $payload, ?string $rootCompositionId): Composition
    {
        return Composition::create([
            'template' => $this->template,
            'label' => $this->compositionLabel($payload),
            'payload' => [
                ...$prompt->toArray(),
                ...$payload,
            ],
            'prompt' => $prompt->prompt,
            'user_id' => auth()->id(),
            'team_id' => auth()->user()->current_team_id,
            'root_composition_id' => $rootCompositionId,
        ]);
    }

    private function saveResponse(CreateResponse $response, Composition $composition): CompositionResult
    {
        $tokensUsage = $response->usage;
        $creditsUsage = [
            'prompt_credits' => $this->convertTokenLengthToWordCredits($tokensUsage->promptTokens),
            'completion_credits' => $this->convertTokenLengthToWordCredits($tokensUsage->completionTokens),
            'total_credits' => $this->convertTokenLengthToWordCredits($tokensUsage->promptTokens + $tokensUsage->completionTokens),
        ];
        $result = CompositionResult::create([
            'remote_id' => $response->id,
            'type' => $response->object,
            'model' => $response->model,
            'usage' => [
                ...$response->usage->toArray(),
                ...$creditsUsage,
            ],
            'composition_id' => $composition->id,
        ]);

        $composition->usage()->create([
            'prompt_credits' => $creditsUsage['prompt_credits'],
            'completion_credits' => $creditsUsage['completion_credits'],
            'total_credits' => $creditsUsage['total_credits'],
            'user_id' => auth()->user()->id,
            'team_id' => auth()->user()->current_team_id,
            'organization_id' => auth()->user()->organization_id,
            'extras' => [
                'tokens_usage' => $tokensUsage->toArray(),
            ]
        ]);

        $choiceCollection = collect($response->choices)->map(function ($choice) use ($result) {
            $text = $choice->text;
            $extras = $choice->toArray();
            unset($extras['text']);
            return [
                'composition_result_id' => $result->id,
                'text' => trim($text),
                'extras' => $extras,
            ];
        });

        $result->choices()->createMany($choiceCollection->toArray());
        return $result;
    }

    public function fake()
    {
        $this->doFake = true;
    }

    protected function fakeResponse(CompositionPrompt $prompt): CreateResponse
    {
        $faker = Factory::create();
        $variations = $prompt->n;
        $length = ceil($this->convertWordCreditsToTokensLength($prompt->maxTokens) / 100);
        $choices = collect(range(0, $variations - 1))->map(function ($idx) use ($faker, $length) {
            return [
                'text' => $faker->paragraphs($faker->numberBetween($length, ceil($length)), true),
                'index' => $idx,
                'logprobs' => null,
                'finish_reason' => 'length',
            ];
        })->toArray();
        $promptTokens = $faker->numberBetween(10, 80);
        $completionTokens = $faker->numberBetween(20, 30 * count($choices));
        $usage = [
            'prompt_tokens' => $promptTokens,
            'completion_tokens' => $completionTokens,
            'total_tokens' => $promptTokens + $completionTokens,
        ];
        $attributes = [
            'id' => $faker->uuid(),
            'object' => 'text_completion',
            'created' => $faker->dateTime()->getTimestamp(),
            'model' => $prompt->model,
            'choices' => $choices,
            'usage' => $usage,
        ];

        return CreateResponse::from($attributes);
    }

    protected function convertWordCreditsToTokensLength(int $wordCredits): int
    {
        // 100 words ~= 75 tokens
        return ceil($wordCredits * 0.75);
    }

    protected function convertTokenLengthToWordCredits(int $tokenLength): int
    {
        // 75 tokens ~= 100 words
        return ceil($tokenLength / 0.75);
    }

    protected function compositionLengthToTokens(string $length): int
    {
        return match ($length) {
            'medium' => $this->convertWordCreditsToTokensLength(180),
            'long' => $this->convertWordCreditsToTokensLength(240),
            default => $this->convertWordCreditsToTokensLength(130)
        };
    }
}

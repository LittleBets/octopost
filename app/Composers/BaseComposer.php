<?php

namespace App\Composers;

use App\Models\Composition;
use App\Models\CompositionResult;
use Faker\Factory;
use OpenAI\Client as OpenAIClient;
use OpenAI\Responses\Completions\CreateResponse;

abstract class BaseComposer implements ComposerContract
{
    protected bool $doFake = false;

    function __construct(protected OpenAIClient $client, protected string $template)
    {
    }

    abstract protected function prompt(array $payload): CompositionPrompt;

    public function compose(array $payload, ?string $parentId = null): CompositionResult
    {
        $prompt = $this->prompt($payload);
        $promptResponse = $this->complete($prompt);
        $composition = $this->savePrompt($prompt, $payload, $parentId);
        return $this->saveResponse($promptResponse, $composition);
    }

    protected function complete(CompositionPrompt $prompt): CreateResponse
    {
        if ($this->doFake) {
            return $this->fakeResponse($prompt);
        } else {
            return $this->client->completions()->create($prompt->toArray());
        }
    }

    private function savePrompt(CompositionPrompt $prompt, array $payload, ?string $parentId): Composition
    {
        return Composition::create([
            'template' => $this->template,
            'payload' => [
                ...$prompt->toArray(),
                ...$payload,
            ],
            'composer_id' => auth()->id(),
            'parent_composition_id' => $parentId,
        ]);
    }

    private function saveResponse(CreateResponse $response, Composition $composition): CompositionResult
    {
        $result = CompositionResult::create([
            'remote_id' => $response->id,
            'type' => $response->object,
            'model' => $response->model,
            'usage' => $response->usage,
            'composition_id' => $composition->id,
        ]);

        $choiceCollection = collect($response->choices)->map(function ($choice) use ($result) {
            $text = $choice->text;
            $extras = $choice->toArray();
            unset($extras['text']);
            return [
                'composition_result_id' => $result->id,
                'text' => $text,
                'extras' => $extras,
            ];
        });

        $result->choices()->createMany($choiceCollection->toArray());
        return $result->refresh();
    }

    public function fake()
    {
        $this->doFake = true;
    }

    public function fakeResponse(CompositionPrompt $prompt): CreateResponse
    {
        $faker = Factory::create();
        $count = $faker->numberBetween(0, $prompt->n);
        $choices = collect(range(0, $count))->map(function ($idx) use ($faker) {
            return [
                'text' => $faker->paragraphs($faker->numberBetween(1, 3), true),
                'index' => $idx,
                'logprobs' => null,
                'finish_reason' => 'length',
            ];
        })->toArray();
        $promptToken = $faker->numberBetween(10, 80);
        $completionToken = $faker->numberBetween(20, 30 * count($choices));
        $usage = [
            'prompt_tokens' => $promptToken,
            'completion_tokens' => $completionToken,
            'total_tokens' => $promptToken + $completionToken,
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
}

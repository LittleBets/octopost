<?php

namespace App\Tuners;

use App\Models\TunedModel;
use Illuminate\Support\Collection;

class ResponseTuner extends BaseTuner
{
    protected function makeTuneLines(array $payload): Collection
    {
        return collect($payload['examples'])->map(function ($example) {
            return $this->tuneLine($example);
        });
    }

    protected function tuneLine(array $example): string
    {
        $prompt = json_encode(sprintf("message:%s%s", $example['message'], config('consts.response_separator')));
        $completion = json_encode(sprintf("%s ", trim($example['response'])));
        return json_encode(['prompt' => $prompt, 'completion' => $completion]);
    }

    protected function baseTunedModelName(string $teamId, array $payload): string
    {
        return $this->baseTunedModel($teamId, $payload)?->base_model ?? 'davinci';
    }

    protected function baseTunedModel(string $teamId, array $payload): TunedModel | null
    {
        return TunedModel::where('team_id', $teamId)->where('composition_type_id', $this->compositionTypeId($payload))->first();
    }

    protected function compositionTypeId(array $payload): string
    {
        return 'response.'.$payload['response_type'];
    }
}
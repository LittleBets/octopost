<?php

namespace App\Composers;

use App\Models\TunedModel;
use Illuminate\Support\Str;

class ResponseComposer extends BaseComposer
{
    public function prompt(array $payload): CompositionPrompt
    {
        $audience = $payload['audience'] ?? null;
        $addressingLiteral = $audience ? " for {$audience}" : ' ';
        $tone = $payload['tone'] ?? null;
        $toneLiteral = $tone ? " in a {$tone} tone" : ' ';
        $model = $this->modelName($payload);
        $responseType = $payload['response_type'];
        $message = trim($payload['message']);

        return CompositionPrompt::from([
            'model' => $model,
            'prompt' => sprintf("Reply to this %s%s%s:%s%s%s", $responseType, $toneLiteral, $addressingLiteral, PHP_EOL, $message, config('consts.response_separator')),
//            'prompt' => sprintf("Write a %s %s %s%smessage:%s%s%s", $tone, $payload['response_type'], $addressingLiteral, PHP_EOL, trim($payload['message']), PHP_EOL, config('consts.response_separator')),
            'max_tokens' => $this->compositionLengthToTokens($payload['composition_length']),
            'temperature' => 0.7,
            'n' => $payload['variations'],
        ]);
    }

    protected function compositionLabel(array $payload): string
    {
        return Str::substr($payload['message'], 0, 20) . '...';
    }

    public function modelName(array $payload): string
    {
        $payloadModel = $payload['model'] ?? null;
        if($payloadModel != null) {
            return $payloadModel;
        }
        return 'text-davinci-002';
//        return TunedModel::where('composition_type_id', $this->compositionTypeId($payload))
//            ->where('team_id', auth()->user()->current_team_id)
//            ->where('status', 'succeeded')
//            ->first()
//            ->fine_tuned_model_name ?? 'text-davinci-002';
    }

    protected function compositionTypeId(array $payload): string
    {
        return 'response.'.$payload['response_type'];
    }
}

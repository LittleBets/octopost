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
        $message = trim($payload['message'] ?? null);
        $points = $payload['points_to_include'] ?? null;
        $pointsLiteral = $points ? sprintf("%sinclude these points:%s%s", PHP_EOL, PHP_EOL, $points) : '';
        $prompt = '';
        if($message != null) {
            $prompt = sprintf("Reply to this %s%s%s%s%s%s%s", $responseType, $toneLiteral, $addressingLiteral, $pointsLiteral, PHP_EOL, PHP_EOL, $message);
        } else {
            $prompt = sprintf("Write a %s%s%s%s", $responseType, $toneLiteral, $addressingLiteral, $pointsLiteral);
        }

        return CompositionPrompt::from([
            'model' => $model,
            'prompt' => sprintf("%s%s", $prompt, config('consts.response_separator')),
//            'prompt' => sprintf("Reply to this %s%s%s:%s%s%s", $responseType, $toneLiteral, $addressingLiteral, PHP_EOL, $message, config('consts.response_separator')),
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
        return 'text-davinci-003';
//        return TunedModel::where('composition_type_id', $this->compositionTypeId($payload))
//            ->where('team_id', auth()->user()->current_team_id)
//            ->where('status', 'succeeded')
//            ->first()
//            ->fine_tuned_model_name ?? 'text-davinci-003';
    }

    protected function compositionTypeId(array $payload): string
    {
        return 'response.'.$payload['response_type'];
    }
}

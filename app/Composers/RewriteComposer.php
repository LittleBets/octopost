<?php

namespace App\Composers;

use Illuminate\Support\Str;

class RewriteComposer extends BaseComposer
{
    public function prompt(array $payload): CompositionPrompt
    {
        $audience = $payload['audience'] ?? null;
        $addressingLiteral = $audience ? " for {$audience}" : ' ';
        $tone = $payload['tone'] ?? null;
        $toneLiteral = $tone ? "In a {$tone} tone, " : '';
        $rewriteType = $payload['rewrite_type'];
        $model = $this->modelName($payload);
        $text = trim($payload['text']);
        $prompt = sprintf("%s%s this%s%s%s%s", $toneLiteral, $rewriteType, $addressingLiteral, PHP_EOL, PHP_EOL, $text);

        return CompositionPrompt::from([
            'model' => $model,
            'prompt' => sprintf("%s%s", $prompt, config('consts.response_separator')),
            'max_tokens' => $this->compositionLengthToTokens($payload['composition_length']),
            'temperature' => 0.5,
            'n' => $payload['variations'],
        ]);
    }

    protected function compositionLabel(array $payload): string
    {
        return Str::substr($payload['text'], 0, 20) . '...';
    }

    public function modelName(array $payload): string
    {
        $payloadModel = $payload['model'] ?? null;
        if($payloadModel != null) {
            return $payloadModel;
        }
        return 'text-davinci-003';
    }
}

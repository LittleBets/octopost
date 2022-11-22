<?php

namespace App\Composers;

use Illuminate\Support\Str;

class SummaryComposer extends BaseComposer
{
    public function prompt(array $payload): CompositionPrompt
    {
        $outputFormat = $payload['output_format'] ?? 'paragraph';
        $outputFormatLiteral = $outputFormat === 'bullet points' ? ' in bullet points ' : ' ';
        $audience = $payload['audience'] ?? null;
        $addressingLiteral = $audience ? " for {$audience}" : ' ';
        $model = $this->modelName($payload);
        $text = trim($payload['text']);

        return CompositionPrompt::from([
            'model' => $model,
            'prompt' => sprintf("Summarize this%s%s:%s%s%s", $outputFormatLiteral, $addressingLiteral, PHP_EOL, $text, config('consts.response_separator')),
            'max_tokens' => $this->compositionLengthToTokens($payload['composition_length']),
            'temperature' => 0.7,
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
        if ($payloadModel != null) {
            return $payloadModel;
        }
        $audience = $payload['audience'] ?? null;
        return $audience == null ? 'text-curie-001' : 'text-davinci-002';
    }
}

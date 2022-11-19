<?php

namespace App\Composers;

use App\Models\Composition;
use App\Models\CompositionResult;

interface ComposerContract
{
    /**
     * @return array{composition: Composition, result: CompositionResult}
     */
    public function compose(array $payload, ?string $rootCompositionId = null): array;
    public function prompt(array $payload): CompositionPrompt;
    public function fake();
}

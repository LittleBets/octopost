<?php

namespace App\Composers;

use App\Models\CompositionResult;

interface ComposerContract
{
    public function compose(array $payload): CompositionResult;
}

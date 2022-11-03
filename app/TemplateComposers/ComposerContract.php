<?php

namespace App\TemplateComposers;

interface ComposerContract
{
    public function compose(array $payload): array;
}

<?php

namespace App\Tuners;

use App\Models\TunedModel;

interface TunerContract
{
    public function upload(array $payload);
    public function tune(TunedModel $tunedModel);
    public function fake();
}
<?php

namespace App\Jobs;

use App\Composers\TunerFactory;
use App\Models\TunedModel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessFineTuning implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public readonly string $template, public readonly TunedModel $tunedModel)
    {
    }

    public function handle(TunerFactory $factory)
    {
        $tuner = $factory->make($this->template);
        $tunedModel = $tuner->tune($this->tunedModel);
        UpdateFineTuningStatus::dispatch($tunedModel)->delay(now()->addMinutes(1));
    }
}

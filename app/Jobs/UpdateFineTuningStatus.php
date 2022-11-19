<?php

namespace App\Jobs;

use App\Models\TunedModel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use OpenAI\Client;

class UpdateFineTuningStatus implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public readonly TunedModel $tunedModel)
    {
    }

    public function handle(Client $client)
    {
        $fineTuneResponse = $client->fineTunes()->retrieve($this->tunedModel->fine_tune_id);
        $status = $fineTuneResponse->status;
        $this->tunedModel->status = $status;
        if ($status == 'succeeded') {
            $this->tunedModel->fine_tuned_model_name = $fineTuneResponse->fineTunedModel;
            $this->tunedModel->save();
        } else if ($status == 'failed') {
            // todo: handle failed fine tuning
        } else {
            $this->tunedModel->save();
            UpdateFineTuningStatus::dispatch($this->tunedModel)
                ->onConnection($this->connection)
                ->onQueue($this->queue)
                ->delay(now()->addMinutes(2));
        }
    }
}

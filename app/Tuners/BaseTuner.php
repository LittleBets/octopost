<?php

namespace App\Tuners;

use App\Jobs\ProcessFineTuning;
use App\Models\TunedModel;
use App\Status;
use Illuminate\Support\Collection;
use OpenAI\Client as OpenAIClient;
use OpenAI\Responses\Files\CreateResponse;
use OpenAI\Responses\FineTunes\RetrieveResponse;

abstract class BaseTuner implements TunerContract
{
    protected bool $doFake = false;

    function __construct(protected OpenAIClient $client, protected string $template)
    {
    }

    public function upload(array $payload)
    {
        $tuneLines = $this->makeTuneLines($payload)->join(PHP_EOL);
        clock($tuneLines);
        return null;
        if ($this->doFake) {
            return null;
        } else {
            $user = auth()->user();
            $teamId = $user->current_team_id;
            $baseModelName = $this->baseTunedModelName($teamId, $payload);
            $uploadResponse = $this->uploadFileForFineTuning($tuneLines);
            $compositionTypeId = $this->compositionTypeId($payload);
            clock($uploadResponse);
            $tuneModel = TunedModel::create([
                'base_model_name' => $baseModelName,
                'composition_type_id' => $compositionTypeId,
                'training_file_id' => $uploadResponse->id,
                'status' => Status::TRAINING,
                'team_id' => $teamId,
                'user_id' => $user->id,
            ]);
            ProcessFineTuning::dispatch($this->template, $tuneModel)->delay(now()->addMinutes(1));
        }
    }

    public function tune(TunedModel $tunedModel)
    {
        $fineTuneResponse = $this->createFineTuneJob($tunedModel);
        $tunedModel->fine_tune_id = $fineTuneResponse->id;
        $tunedModel->status = $fineTuneResponse->status;
        $tunedModel->save();
        return $tunedModel;
    }

    private function createFineTuneJob(TunedModel $tunedModel): RetrieveResponse
    {
        $baseModelName = $tunedModel->base_model_name;
        $trainingFileId = $tunedModel->training_file_id;
        $fineTunedModelName = $tunedModel->fine_tuned_model_name;

        return $this->client->fineTunes()->create([
            'training_file' => $trainingFileId,
            'model' => $fineTunedModelName ?? $baseModelName,
            'n_epochs' => $this->fineTuningEpochs(),
            'suffix' => $tunedModel->id,
        ]);
    }

    protected function fineTuningEpochs(): int
    {
        return 1;
    }

    public function uploadFileForFineTuning(string $lines) : CreateResponse
    {
        $fp = $this->makeFilePointer($lines);
        return $this->client->files()->upload([
            'purpose' => 'fine-tune',
            'file' => $fp,
        ]);
    }

    public function fake()
    {
        $this->doFake = true;
    }

    private function makeFilePointer(string $lines)
    {
        $temp = tmpfile();
        clock($temp);
        fwrite($temp, $lines);
        return $temp;
    }

    abstract protected function makeTuneLines(array $payload): Collection;
    abstract  protected function baseTunedModelName(string $teamId, array $payload): string;
    abstract protected  function compositionTypeId(array $payload): string;
}
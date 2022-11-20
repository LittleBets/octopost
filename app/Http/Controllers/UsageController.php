<?php

namespace App\Http\Controllers;

use App\Composers\ComposerFactory;
use App\Models\Usage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Inertia\Inertia;

class UsageController extends Controller
{
    public function show()
    {
        $user = auth()->user();
        $usage = Usage::teamUsageByInterval('month')
            ->where('organization_id', $user->organization_id)
            ->get();
        return Inertia::render('Usage/UsageShow', [
            'usageByTeams' => $usage,
        ]);
    }

    public function guess(Request $request, ComposerFactory $factory): JsonResponse
    {
        $composer = $factory->make($request->input('template'));
        // we are using "usage" model to make queries lighter
        $compositionPrompt = $composer->prompt([...$request->input('payload'), 'model' => 'usage']);
        $prompt = $compositionPrompt->prompt;
        if(strlen($prompt) === 0) {
            return response()->json([
                'prompt_credits' => 0,
                'completion_credits' => 0,
                'total_credits' => 0,
            ]);
        }
        $counterResponse = Http::post(config('app.token_counter_endpoint'), ['prompt' => $prompt,]);
        if ($counterResponse->ok()) {
            $promptToken = $counterResponse['length'];
            $completionToken = $compositionPrompt->maxTokens  * $compositionPrompt->n;
            return response()->json([
                'prompt_credits' => $this->convertTokenLengthToWordCredits($promptToken),
                'completion_credits' => $this->convertTokenLengthToWordCredits($completionToken),
                'total_credits' => $this->convertTokenLengthToWordCredits($promptToken + $completionToken),
            ]);
        }
        return abort(400);
    }

    private function convertTokenLengthToWordCredits(int $tokenLength): int
    {
        return ceil($tokenLength / 0.75);
    }
}

<?php

namespace App\Http\Controllers;

use App\Composers\ComposerContract;
use Facades\App\Composers\ComposerFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class UsageController extends Controller
{
    public function guess(Request $request): JsonResponse
    {
        /** @var ComposerContract $composer */
        $composer = ComposerFactory::make($request->input('template'));
        $compositionPrompt = $composer->prompt($request->input('payload'));
        $prompt = $compositionPrompt->prompt;
        $counterResponse = Http::post(config('app.token_counter_endpoint'), ['prompt' => $prompt,]);
        if ($counterResponse->ok()) {
            $promptToken = $counterResponse['length'];
            $completionToken = $compositionPrompt->maxTokens  * $compositionPrompt->n;
            return response()->json([
                'prompt_tokens' => $promptToken,
                'completion_tokens' => $completionToken,
                'total_tokens' => $promptToken + $completionToken,
            ]);
        }
        return abort(400);
    }
}

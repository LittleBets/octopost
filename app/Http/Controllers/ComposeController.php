<?php

namespace App\Http\Controllers;

use App\Composers\ComposerContract;
use App\Http\Resources\CompositionResultChoiceResource;
use Facades\App\Composers\ComposerFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ComposeController extends Controller
{
    public function show(Request $request): Response
    {
        return Inertia::render('Compose/ComposeShow', [
            'model' => $request->input('model'),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        /** @var ComposerContract $composer */
        $composer = ComposerFactory::make($request->input('template'));
        $modelType = $request->input('model');
        if ($modelType === 'fake') {
            $composer->fake();
        }
        $compositionResult = $composer->compose($request->input('payload', []), $request->input('composition_parent_id'));
        return response()->json([
            'parent_composition_id' => $compositionResult->composition_id,
            'choices' => CompositionResultChoiceResource::collection($compositionResult->choices),
        ]);
    }
}

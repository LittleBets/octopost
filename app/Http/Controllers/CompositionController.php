<?php

namespace App\Http\Controllers;

use App\Composers\ComposerContract;
use App\Http\Resources\CompositionResultResource;
use App\Models\Composition;
use App\Models\CompositionResult;
use Facades\App\Composers\ComposerFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CompositionController extends Controller
{
    public function show(Request $request): Response
    {
        return Inertia::render('Compose/ComposeShow', [
            'model' => $request->input('model'),
        ]);
    }
    public function compose(Request $request): Response
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
        $rootCompositionId = $request->input('root_composition_id');
        $payload = [...$request->input('payload'), 'model' => $modelType];
        ['composition' => $composition, 'result' => $result] = $composer->compose($payload, $rootCompositionId);

        $result = CompositionResult::with(['choices'])->find($result->id);
//        $newCompositionResult = Composition::with(['result.choices', 'childrenCompositions.result.choices'])
//            ->find($newCompositionId);
        $childrenCount = Composition::where('root_composition_id', $rootCompositionId ?? $result->composition_id)->count();
        return response()->json([
            'composition_version' => $childrenCount + 1,
            'root_composition_id' => $rootCompositionId ?? $result->composition_id,
            'result' => new CompositionResultResource($result),
            'composition_label' => $composition->label
        ]);
    }

    public function update(Request $request)
    {
        $newAttributes = $request->all(['label']);
        Composition::where('id', $request->composition_id)->update($newAttributes);
        return response()->json($newAttributes);
    }
}

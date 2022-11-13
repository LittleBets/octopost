<?php

namespace App\Http\Controllers;

use App\Composers\ComposerContract;
use App\Http\Resources\CompositionResource;
use App\Http\Resources\CompositionResourceMinimal;
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
    public function showAll(Request $request): Response
    {
        $compositions = Composition::where('team_id', auth()->user()->current_team_id)
            ->where('root_composition_id', null)
            ->where('team_id', auth()->user()->current_team_id)
            ->with('user')
            ->withCount(['childrenCompositions as versions'])
            ->latest()
            ->get();
        return Inertia::render('Composition/CompositionShowAll', [
            'compositions' => CompositionResourceMinimal::collection($compositions)
        ]);
    }

    public function showOne(Request $request): Response
    {
        $composition = Composition::with(['childrenCompositions' => function ($query) {
            $query->with(['result.choices', 'user'])->latest();
        }, 'result.choices', 'user'])
            ->where('team_id', auth()->user()->current_team_id)
            ->findOrFail($request->composition_id);
        return Inertia::render('Composition/CompositionShowOne', [
            'composition' => new CompositionResource($composition)
        ]);
    }

    public function compose(Request $request): Response
    {
        return Inertia::render('Compose/ComposeNew', [
            'model' => $request->input('model', 'fake'),
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
        Composition::where('id', $request->composition_id)
            ->where('team_id', auth()->user()->current_team_id)
            ->update($newAttributes);
        return response()->json($newAttributes);
    }
}

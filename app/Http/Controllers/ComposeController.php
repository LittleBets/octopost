<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use OpenAI\Client;

class ComposeController extends Controller
{
    public function show(): Response
    {
        return Inertia::render('Compose/ComposeShow');
    }

    public function store(Client $client, Request $request): JsonResponse
    {
        clock($request->all());
        $clientPayload = $this->composer($request->input('template'))->compose($request->get('payload'));
        clock($clientPayload);
        $result = $client->completions()->create($clientPayload);
        clock($result);
        return response()->json(['success' => true]);
    }

    public function composer(string $template)
    {
        return resolve(sprintf('%s-composer', $template));
    }
}

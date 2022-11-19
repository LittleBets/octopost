<?php

namespace App\Http\Controllers;

use App\Composers\TunerFactory;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TuningController extends Controller
{
    public function tune(Request $request)
    {
        return Inertia::render('Tuning/TuningNew', [
            'model' => $request->input('model'),
        ]);
    }

    public function store(Request $request, TunerFactory $factory)
    {
        $tuner = $factory->make($request->input('template'));
        $modelType = $request->input('model_type');
        if($modelType === 'fake') {
            $tuner->fake();
        }
        $payload = [...$request->all(), 'model' => $modelType];
        $tuner->upload($payload);

        return back();
    }
}

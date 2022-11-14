<?php

namespace App\Http\Controllers;

use App\Models\CompositionResultChoice;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CompositionResultChoiceController extends Controller
{
    public function update(Request $request)
    {
        $newAttributes = $request->all(['text']);
        CompositionResultChoice::where('id', $request->composition_result_choice_id)
            ->whereHas('composition', function ($query) {
                $query->where('team_id', auth()->user()->current_team_id);
            })
            ->update($newAttributes);
        return back()->with($newAttributes);
    }

    public function destroy(Request $request)
    {
        CompositionResultChoice::where('id', $request->composition_result_choice_id)->delete();
        return back();
    }
}

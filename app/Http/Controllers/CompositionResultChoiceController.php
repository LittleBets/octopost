<?php

namespace App\Http\Controllers;

use App\Models\CompositionResultChoice;
use Illuminate\Http\Request;

class CompositionResultChoiceController extends Controller
{
    public function update(Request $request)
    {
        $newAttributes = $request->all(['text']);
        CompositionResultChoice::where('id', $request->composition_result_choice_id)->update($newAttributes);
        return response()->json($newAttributes);
    }

    public function destroy(Request $request)
    {
        CompositionResultChoice::where('id', $request->composition_result_choice_id)->delete();
        return response()->json();
    }
}

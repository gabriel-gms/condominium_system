<?php

namespace App\Http\Controllers;

use App\Models\FoundAndLost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FoundAndLostController extends Controller
{
    public function getAll(){
        $array['error'] = false;

        $lost = FoundAndLost::where('status', 'LOST')->get();
        $found = FoundAndLost::where('status', 'FOUND')->get();

        $array['found'] = $found;
        $array['lost'] = $lost;

        return response()->json($array);
    }

    public function setFoundAndLost(Request $request){
        $validator = Validator::make($request->all(), [
            'where' => 'required',
            'description' => 'required',
            'photos' => 'file|mimes:jpg,png'
        ]);

        if($validator->fails()){
            return response()->json([
                'error' => true,
                'message' => $validator->errors()->first()
            ]);
        }

        $foundAndLost = new FoundAndLost();
        $foundAndLost->where = $request->input('where');
        $foundAndLost->description = $request->input('description');
        $foundAndLost->created_at = date('y-m-d');
        $foundAndLost->photos = $request->input('photos');
        $foundAndLost->save();

        return response()->json([
            'error' => false,
            'losts' => $foundAndLost
        ]);
    }
}

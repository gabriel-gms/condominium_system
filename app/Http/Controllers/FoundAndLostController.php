<?php

namespace App\Http\Controllers;

use App\Models\FoundAndLost;
use Illuminate\Http\Request;

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
}

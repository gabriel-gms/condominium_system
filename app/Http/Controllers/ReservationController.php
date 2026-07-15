<?php

namespace App\Http\Controllers;

use App\Models\Area;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function getResevations(){
        $reservations = Area::where('allowed', 1)->get();

        return response()->json([
            'error' => false,
            'reservations' => $reservations  
        ]);
    }
}

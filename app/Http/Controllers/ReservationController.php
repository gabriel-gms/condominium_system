<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReservationController extends Controller
{
    public function getReservations(){
        $reservations = Area::where('allowed', 1)->get();

        return response()->json([
            'error' => false,
            'reservations' => $reservations  
        ]);
    }

    public function createReservation($id_unit, Request $request){
        $validator = Validator::make($request->all(), [
            'id_area' => 'required',
            'date' => 'required|date_format:Y-m-d',
            'start' => 'required|date_format:H:i:s',
            'end' => 'required|date_format:H:i:s'
        ]);
        if($validator->fail()){
            return response()->json([
                'error' => true,
                'message' => $validator->errors()->first()
            ]);
        }

        $unit = Unit::find('id', $id_unit);
        $area = Area::find('id', $request->input('id_area'));

        if($unit && $area){
            $start = $request->input('start');
            $end = $request->input('end');
            $date = $request->input('date');

            $week_day = date('w', strtotime($date));
            $allow_day = explode(',', $area['days']);
            if(){

            } else {
                return
            }
        } else {
            return response()->json([
                'error' => true,
                'message' => 'unit or area dont exists'
            ])
        }
    }
}

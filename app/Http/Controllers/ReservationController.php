<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReservationController extends Controller
{
    public function getReservations(){
        $areas = Area::where('allowed', 1)->get();

        $days_helper = ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab'];
    
        foreach($areas as $area){
            $allowed_days = explode(',', $area['days']);
            $start_day = intval(current($allowed_days));
            $available_days = [];
            $available_days[] = $days_helper[$start_day];
            array_shift($allowed_days);
            
            for($i = 0; $i<count($allowed_days) - 1; $i++){
                $reload_sequence = false;
                if(intval($allowed_days[$i]) + 1 !== intval($allowed_days[$i + 1])){
                    $available_days[] = $days_helper[$allowed_days[$i]];
                    $reload_sequence = true;
                    if($reload_sequence) $available_days[] = $days_helper[$allowed_days[$i+1]];
                } else {
                    $available_days[] = $days_helper[$allowed_days[$i]];
                }
            }
            $available_days[] = $days_helper[end($allowed_days)];
            echo "Area ".$area->title."\n";
            print_r($available_days);
        }

        return response()->json([
            'error' => false 
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

        $unit = Unit::find($id_unit);
        $area = Area::find($request->input('id_area'));

        if($unit && $area){
            $start = $request->input('start');
            $end = $request->input('end');
            $date = $request->input('date');

            $week_day = date('w', $date);
            $allow_days = explode(',', $area['days']);
            if(in_array($week_day, $allow_days)){
                if($start < $end && $end < $area['end_time'] - 1 && $start >= $area['start_time']){
                    
                } else {
                    return response()->json([
                        'error' => true,
                        'message' => 'incorrect hour'
                    ]);
                }
            } else {
                return response()->json([
                    'error' => true,
                    'message' => 'your day is unavailable'
                ]);
            }
        } else {
            return response()->json([
                'error' => true,
                'message' => 'unit or area dont exists'
            ]);
        }
    }
}

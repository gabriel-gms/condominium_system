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
            $day_list = explode(',', $area['days']);

            $days_groups = [];

            $last_day = intval(current($day_list));
            $days_groups[] = $days_helper[$last_day];
            array_shift($day_list);

            foreach($day_list as $day){
                if(intval($day) != $last_day+1){
                    $days_groups[] = $days_helper[$last_day];
                    $days_groups[] = $days_helper[$day];
                }
                $last_day = intval($day);
            }

            $days_groups[] = $days_helper[end($day_list)];

            $day_week = '';
            $date = [];
            $count_double = 1;
            foreach($days_groups as $day){
                if($count_double <= 1){
                    $day_week .= $day."-";
                    $count_double++;
                } else {
                    $day_week .= $day;
                    $count_double--;
                    array_push($date, $day_week);
                    $day_week = '';
                }

            }

            $start = date('H:i', strtotime($area['start_time']));
            $end = date('H:i', strtotime($area['end_time']));
            foreach($date as $date_key => $hour){
                $date[$date_key] .= " de ".$start." as ".$end;
            }

            $array[] = [
                'id' => $area['id'],
                'cover' => asset('storage/'.$area['cover']),
                'title' => $area['title'],
                'dates' => $date
            ];
        }

        return response()->json([
            'error' => false,
            'areas' => $array 
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

    public function getDisabledDates($id_area){
        $area = Area::find($id_area);
        if(!$area || $id_area == '' || $id_area === null){
            return response()->json([
                'error' => true,
                'message' => 'area do not exists'
            ]);
        }

        $disable_days = [];
        for($i=0;$i<7;$i++){
            if(!in_array($i, explode(',',$area['days']))){
                array_push($disable_days, $i);
            }
        }

        $array_disable_days = [];
        $date_now = time();
        $three_months_later = strtotime('+3 months');
        for(
            $date_now; 
            $date_now < $three_months_later; 
            $date_now = strtotime('+1 day', $date_now)
        ){
            $week_day = date('w', $date_now);
            if(in_array($week_day, $disable_days)){
                $array_disable_days[] = date('Y-m-d', $date_now);
            }
        }

        print_r($array_disable_days);
    }
}

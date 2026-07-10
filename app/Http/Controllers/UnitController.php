<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use App\Models\UnitPeople;
use App\Models\UnitPet;
use App\Models\UnitVehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use function Laravel\Prompts\error;

class UnitController extends Controller
{
    public function getByIdUnit($id){
        //$id = $request->input('id');
        //if(!$id){return response()->json(['error' => true, 'message' => 'not declared unit']);}

        $unit_by_id = Unit::where('id', $id)->first();
        $people_by_id_unit = UnitPeople::where('id_unit', $unit_by_id->id)->get();
        $vehicles_by_id_unit = UnitVehicle::where('id_unit', $unit_by_id->id)->get();
        $pets_by_id_unit = UnitPet::where('id_unit', $unit_by_id->id)->get();
        
        return response()->json([
            'error' => false,
            'unit' => [ 'id' => $unit_by_id->id, 'name' => $unit_by_id->name ],
            'people' => $people_by_id_unit,
            'vehicles' => $vehicles_by_id_unit,
            'pets' => $pets_by_id_unit
        ]);
    }

    public function createPersonInUnit($id_unit, Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:2|string',
            'birthdate' => 'required|date',
        ]);
        if($validator->fails()){
            return response()->json($validator->errors()->first());
        }

        $person = new UnitPeople();
        $person->name = $request->input('name');
        $person->birthdate = $request->input('birthdate');
        $person->id_unit = $id_unit;
        $person->save();

        return response()->json([
           'error' => false,
           'person' => $person 
        ]);
    }
}

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
            return response()->json(['error' => true, 'message' => $validator->errors()->first()]);
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

    public function createPetInUnit($id_unit, Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:2|string',
            'race' => 'required',
        ]);
        if($validator->fails()){
            return response()->json(['error' => true, 'message' => $validator->errors()->first()]);
        }

        $pets = new UnitPet();
        $pets->name = $request->input('name');
        $pets->race = $request->input('race');
        $pets->id_unit = $id_unit;
        $pets->save();

        return response()->json([
           'error' => false,
           'pets' => $pets 
        ]);
    }

    public function createVehicleInUnit($id_unit, Request $request){
        $validator = Validator::make($request->all(), [
            'title' => 'required|min:2|string',
            'color' => 'required|string',
            'plate' => 'required|string'
        ]);
        if($validator->fails()){
            return response()->json(['error' => true, 'message' => $validator->errors()->first()]);
        }

        $vehicles = new UnitVehicle();
        $vehicles->title = $request->input('title');
        $vehicles->color = $request->input('color');
        $vehicles->plate = $request->input('plate');
        $vehicles->id_unit = $id_unit;
        $vehicles->save();

        return response()->json([
           'error' => false,
           'vehicles' => $vehicles 
        ]);
    }

    public function removePersonInUnit($id){
        if(empty($id)){
            return response()->json([
                'eroor' => true,
                'message' => 'id expected'
            ]);
        }

        UnitPeople::where('id', $id)->delete();

        return response()->json([
            'error' => false,
            'message' => 'delete operation success'
        ]);
    }

    public function removePetInUnit($id){
        if(empty($id)){
            return response()->json([
                'eroor' => true,
                'message' => 'id expected'
            ]);
        }

        UnitPet::where('id', $id)->delete();

        return response()->json([
            'error' => false,
            'message' => 'delete operation success'
        ]);
    }

    public function removeVehicleInUnit($id){
        if(empty($id)){
            return response()->json([
                'eroor' => true,
                'message' => 'id expected'
            ]);
        }

        UnitVehicle::where('id', $id)->delete();

        return response()->json([
            'error' => false,
            'message' => 'delete operation success'
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Unit;
use App\Models\Warning;
use DateTime;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Validator as ValidationValidator;

class WarningController extends Controller
{
    public function getMyWarnings(Request $request){
        $array['error'] = false;

        $user = $request->user();
        $units_of_user = Unit::where('id', $request->input('id_param'))->where('id_owner', $user->id)->count();
        if($units_of_user <= '0'){
            $array['error'] = true;
            $array['message'] = 'This property is not yours';
            return response()->json($array);
        }

        $warnings = Warning::where('id_unit', $request->input('id_param'))->get();
        $array['warnings'] = $warnings;
        
        return response()->json($array);
    }

    public function setWarningFile(Request $request){
        $array['error'] = false;

        $validator = Validator::make($request->all(), [
            'photos' => 'required|file|mimes:jpg,png'
        ]);
        if($validator->fails()){
            $array['error'] = true;
            $array['message'] = $validator->errors()->first();
        }

        $file = $request->file('photos')->store('public');
        $array['photos'] = asset(Storage::url($file));
        return response()->json($array);
    }

    public function setWarning(Request $request){
        $array['error'] = false;

        $validator = Validator::make($request->all() ,[
            'title' => 'required|min:3|max:20',
            'body' => 'required',
            'photos' => 'mimes:jpg,png'
        ]);

        if($validator->fails()){
            $array['error'] = true;
            $array['message'] = $validator->errors()->first();
        }

        $user = $request->user();
        $units_of_user = Unit::where('id', $request->input('id_param'))->where('id_owner', $user->id)->count();
        if($units_of_user <= '0'){
            $array['error'] = true;
            $array['message'] = 'This property is not yours';
            return response()->json($array);
        }

        $warning = new Warning();
        $warning->id_unit = $request->input('id_param');
        $warning->title = $request->input('title');
        $warning->body = $request->input('body');
        $warning->created_at = date('Y-m-d');
        $warning->photos = $request->input('photos');
        $warning->save();

        $array['warning'] = $warning;
        return response()->json($array);
    }
}

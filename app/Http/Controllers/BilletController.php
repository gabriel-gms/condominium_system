<?php

namespace App\Http\Controllers;

use App\Models\Billet;
use App\Models\Unit;
use Illuminate\Http\Request;

class BilletController extends Controller
{
    public function getMyBillets(Request $request){
        $array['error'] = false;

        $user = $request->user();
        $unit_of_user = Unit::where('id', $request->input('id_param'))->where('id_owner', $user->id)->count();
        if($unit_of_user <= '0'){
            $array['error'] = true;
            $array['message'] = 'This property is not yours';
            return response()->json($array);
        }

        $billets = Billet::where('id_unit', $request->input('id_param'))->get();
        foreach($billets as $billetKey => $billetValue){
            $billets[$billetKey]['fileurl'] = asset('storage/'.$billetValue['fileurl']);
        }
        $array['billets'] = $billets;
        return response()->json($array);
    }
}

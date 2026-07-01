<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function unauthorized()
    {
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    public function register(Request $request){
        $array = ['error' => false];
        $data = $request->only(['name', 'email', 'cpf', 'password', 'password_confirmation']);
        
        if(!$data){ $array['error'] = true;
            $array['message'] = 'Incomplete or invalid data, please provide name, email, cpf, password and password_confirmation';
            return response()->json($array);
        }
        foreach($data as $key => $value){
            if(!$data[$key]){
                $array['error'] = true;
                $array['message'] = 'Incomplete or invalid data, please provide '.$key;
                return response()->json($array);
            }
            $data[$key] = htmlspecialchars($value);
            $data[$key] = trim($value);
        }
    }
}

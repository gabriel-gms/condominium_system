<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Models\Unit;

class AuthController extends Controller
{
    public function unauthorized()
    {
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    public function register(Request $request){
        $array = ['error' => false];
    
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:50',
            'email' => 'required|string|email|unique:users,email',
            'cpf' => 'required|string|digits:11|unique:users,cpf',
            'password' => 'required|string|min:6',
            'password_confirmation' => 'required|string|min:6|same:password',
        ]);

        if($validator->fails()){
            $array['error'] = $validator->errors()->first();
            return response()->json($array, 400);
        }

        $name = $request->input('name');
        $email = $request->input('email');
        $cpf = $request->input('cpf');
        $password = $request->input('password');
        $hash = Hash::make($password);

        $user = User::create([
            'name' => $name,
            'email' => $email,
            'cpf' => $cpf,
            'password' => $hash,
        ]);
        $array['user'] = $user;

        $properties = Unit::select('id', 'name')
        ->where('id_owner', $user->id)
        ->get();
        
        $array['user']['properties'] = $properties;

        return response()->json($array, 201);
    }

    public function login(Request $request){
        $array = ['error' => false];

        $validator = Validator::make($request->all(), [
            'cpf' => 'required|string|digits:11',
            'password' => 'required|string|min:6',
        ]);

        if($validator->fails()){
            $array['error'] = true;
            $array['message'] = $validator->errors()->first();
            return response()->json($array);
        }

        $cpf = $request->input('cpf');
        $password = $request->input('password');

        $user = User::where('cpf', $cpf)->first();
        if(!$user || !Hash::check($password, $user->password)){
            $array['error'] = true;
            $array['message'] = 'Invalid credentials';
            return response()->json($array);
        }

        $token = $user->createToken('auth_token')->plainTextToken;
        $array['token'] = $token;

        /* Tentativa de rodar o jwt
        if(Auth::guard('api')->attempt(['cpf' => $cpf, 'password' => $password])){
            $token = Auth::attempt(['cpf' => $cpf, 'password' => $password]);
            if(!$token){
                $array['error'] = true;
                $array['message'] = 'Error generating token';
                return response()->json([$array]);
            }
            $array['token'] = $token;

            $user = Auth::guard('api')->user();
            $array['user'] = $user;

            $properties = Unit::select('id', 'name')
            ->where('id_owner', $user->id)
            ->get();

            $array['user']['properties'] = $properties;

        } else {
            $array['error'] = true;
            $array['message'] = 'Invalid credentials';
            return response()->json([$array]);
        }
        */
        return response()->json([$array]);
    }

    public function validateToken(){
        $array = ['error' => false];

        $user = Auth::user();
        if(!$user){
            $array['error'] = true;
            $array['message'] = 'Invalid token';
        }

        return response()->json($array);
    }
}

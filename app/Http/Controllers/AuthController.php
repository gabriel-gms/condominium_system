<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Models\Unit;
use Laravel\Sanctum\PersonalAccessToken;

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

        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'cpf' => $request->input('cpf'),
            'password' => Hash::make($request->input('password')),
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

        $user = User::where('cpf', $request->input('cpf'))->first();
        $validate_password = Hash::check($request->input('password'), $user->password);
        if(!$user || !$validate_password){
            $array['error'] = true;
            $array['message'] = 'Invalid credentials';
            return response()->json($array);
        }

        $token = $user->createToken('auth_token')->plainTextToken;
        $array['token'] = $token;
        $array['user'] = $user;

        return response()->json([$array]);
    }

    public function validateToken(Request $request){
        $array = ['error' => false];

        $user = $request->user();
        if(!$user){
            $array['error'] = true;
            $array['message'] = 'Invalid token';
            return response()->json($array);
        }
        $array['user'] = $user;

        return response()->json($array);
    }

    public function logout(Request $request){
        $validateToken = PersonalAccessToken::findToken($request->bearerToken());

        if($validateToken){
            $validateToken->delete();

            return response()->json([
                'message' => 'token removed'
            ], 201);
        }

        return response()->json([
            'message' => 'invalid token'
        ], 500);
    }
}

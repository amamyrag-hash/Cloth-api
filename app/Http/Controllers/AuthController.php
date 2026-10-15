<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(Request $request){
        $data= $request->validate([
            'name'=>'required|string|max:255',
            'email'=>'required|email|unique:users,email',
            'password'=>'required|string|min:6|' 
            ]);

        $user=User::create($data);
        return response()->json([
            'user'=>$user,
            'token'=>$user->createToken('api')->plainTextToken
        ]);
    }

    public function login(Request $request){
        $data  = $request->validate([
            'email'=>'required|email',
            'password'=>'required'
        ]);

        if(!Auth::attempt($data)){
            return response()->json([
                'message'=>'Invalid email or password'
            ],401);

        }

        $user = Auth::user();

        return response()->json([
            'user'=>$user,
            'token'=>$user->createToken('api')->plainTextToken
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->noContent();
    }
}

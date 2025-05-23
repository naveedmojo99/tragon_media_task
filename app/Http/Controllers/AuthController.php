<?php

namespace App\Http\Controllers;

use App\Models\User;
use Auth;
use Hash;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function register(Request $request)
    {
      $request->validate([
        'name'=>'required|string|max:255',
        'email'=> 'required|string|email|max:255|unique:users',
        'password'=> 'required|string|min:6'
      ]);

      $user = User::create([
        'name'=> $request->name,
        'email'=> $request->email,
        'password'=>Hash::make($request->password)  ,
      ]);

      $token= Auth::login($user);
      return response()->json([
        'status'=>'success',
        'message'=>'User Created succesfully',
        'user'=>$user,
        'authorization'=>[
            'token'=> $token,
            'type'=>'bearer'
        ]
        ]);
        }

        public function login(Request $request) 
        {
            $request->validate([
                'email'=> 'required|string|email',
                'password'=>'required|string'
            ]);   
            
            $credentials=$request->only('email','password');
            $token=Auth::attempt($credentials);
            if(!$token){
                return response()->json([
                    'status'=> 'error',
                    'message'=> "Unauthorized"

                ],401);
                    }
        $user =Auth::user();
        return response()->json([
            "status"=> "success",
            'authorizartion'=>[
                'token'=> $token,
                'type'=>'bearer',
            ]
            ]);
          }


        public function logout()
        {
            Auth::logout();
            return response()->json([
                'status'=> 'success',
                'message'=> 'Succesfully Loged out',
            ]);
        }

        public function refresh()
        {
            return response()->json(
                [
                    'status'=> 'success',
                    'user'=>Auth::user(),
                    'authorisation'=>[
                        'token' => Auth::refresh(),
                        'type'=> 'bearer',
                ]
                    ]);
        }
}

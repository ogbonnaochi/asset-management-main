<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class AuthController extends Controller
{
    //
    
    public function Login (Request $request) {
        $fields = Validator::make($request->all(), [
            'email'=> 'required|string',
            'password'=> 'required|string|min:8',
        ]);
        
        if($fields->fails()) {
            $response = [
                'errors'=> $fields->errors(),
                'success' => false
            ];

            return response($response);
        }

        $user = User::where('email', $request->email)->first();

        if(!$user || !Hash::check($request->password, $user->password)) {
            return response([
                'message' => 'incorrect credentials',
                'success' => false
            ]);
        }
        // else if(is_null($user->email_verified_at)) {
        //     return response([
        //         'message' => 'email not verified',
        //         'success' => false
        //     ], 401);
        // }
        $token = $user->createToken('Personal Access Token', [])->plainTextToken;
        
            
        $response = [
            'user'=> $user,
            'token'=> $token,
            'message'=> 'Login successful',
            'success' => true
        ];

        return response($response, 201);


    }

    public function Register (Request $request) {
        $fields = Validator::make($request->all(),[
            'name'=> 'required|string',
            'email'=> 'required|string|unique:users,email',
            'password'=> 'required|string|min:8',
        ]);


        if($fields->fails()) {
            $response = [
                'errors'=> $fields->errors(),
                'success' => false
            ];

            return response($response);
        }


        $user = User::create([
            'name'=> Str::upper($request['name']),
            'email'=> $request['email'],
            'password' => bcrypt($request['password']),
        ]);
        $response = [
            'user'=> $user,
            'message'=> 'Successful signup',
            'success' => true
        ];

        return response($response);


    }

    public function Logout (Request $request) {
        $request->user()->tokens()->delete();

        return [
            'message'=> 'logged out'
        ];
    }
}

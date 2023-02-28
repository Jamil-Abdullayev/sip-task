<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;

class UserController extends Controller
{
    public function signIn(Request $request) {
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Invalid login details'
            ], 401);
        }
        $user = User::where('email', $request['email'])->firstOrFail();
        $token = $user->createToken('auth_token')->plainTextToken;
        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    public function signUp(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users|max:255',
            'phone' => 'required',
            'password' => 'required',
        ]);
        // Return errors if validation error occur.
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'error' => $errors
            ], 400);
        }
        // Check if validation pass then create user and auth token. Return the auth token
        if ($validator->passes()) {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($request->password)
            ]);
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'access_token' => $token,
                'token_type' => 'Bearer',
            ]);
        }
    }

    public function infoShow(Request $request){

        return $request->user();
    }

    public function infoUpdate(Request $request){
        $user = $request->user();
        $updatingUser = User::find($user->id); //find current user in base
        $updatingUser->phone = $request->phone;
        $updatingUser->email = $request->email;
        $updatingUser->created_at = Carbon::now();
        $updatingUser->save(); //updating and save

        return $updatingUser;
    }
    public function tokenDelete(Request $request){
        $user = $request->user();

        $user->tokens()->delete();

        //$token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Old token deleted, new created',
            //'new_access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    public function latency(){
            $config = [
                'pingHost' => 'google.com'
            ];

            exec("ping -c 1 " . $config['pingHost'], $output);

// return the latency in milliseconds
            return $output;
    }




}

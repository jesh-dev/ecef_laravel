<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class memberController extends Controller
{
    //
    public function register(Request $request) {

        // return "Register User";
        $validator = Validator::make($request->all(),[
            'firstname' => 'required|string',
            'lastname' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'phone_number' => 'required|string',
            'province' => 'in:mainland,mainland1,mainland2,lagos,lagos_mainland',
            'gender' => 'in:male,female',
            'role' => 'in:user,admin',
            'password' => 'string|min:8|regex:/^(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])[a-zA-Z0-9]{8,}$/',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
                'message' => "registration Failed"
            ], 400);
        }

        $user = new User;
        $user->firstname = $request->firstname;
        $user->lastname = $request->lastname;
        $user->email = $request->email;
        $user->phone_number = $request->phone_number;
        $user->province = $request->province;
        $user->gender = $request->gender;
        $user->role = $request->role;
        $user->password = $request->password;
        $user->save();
        return response()->json([
            'user' => $user,
            'message' => 'Registered Successfully'
        ], 201);
    }

        
    // Login 
    public function login(Request $request) {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8'
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            return response()->json([
                'message' => 'login successfully',
                'user' => $user
            ], 200);
        }

        return response()->json([
            'message' => 'Invalid Credentials',
        ], 400);
    }
}

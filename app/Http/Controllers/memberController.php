<?php

namespace App\Http\Controllers;

use App\Mail\ContactMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
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
            'phone_number' => 'required|string|min:8|max:15|regex:/^(?=.*[0-9])[0-9]{8,}$/',
            'province' => 'required|in:mainland,lagos,lagos_mainland_1',
            'branch' => 'required|in:branch_1,branch_2,branch_3',
            'password' => 'string|min:8|regex:/^(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])[a-zA-Z0-9]{8,}$/',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
                'message' => "registration Failed"
            ], 422);
        }

        try {
            //code...
            $verification_code = rand(100000,999999);
            $user = new User;
            $user->firstname = $request->firstname;
            $user->lastname = $request->lastname;
            $user->email = $request->email;
            $user->phone_number = $request->phone_number;
            $user->province = $request->province;
            $user->branch = $request->branch;
            $user->password = $request->password;
            $user->verification_code = $verification_code;
            $user->save();

            Mail::to($user->email)->send(new \App\Mail\UserEmailVerification($user));
            return response()->json([
                'success' => true,
                'user' => $user,
                'message' => 'Registered Successfully'
            ], 200);
            
        } catch (\Exception $error) {
            return response()->json([
                "success" => false,
                'message' => 'Registration Failed',
                'error' => $error
            ], 400);
        }
        
    }

        public function verify(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'code' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 400);
        }
        try {
            $user = User::where('email', $request->email)->first();
            if (!$user) {
                return response()->json([
                    'message' => $request->email . 'do not exist',
                ], 400);
            } elseif ($user->verification_code !== $request->code) {
                return response()->json([
                    'message' => 'verification failed',
                ], 400);
            } elseif (
                $user->email && $user->email ===
                $request->email
            ) {
                User::where('email', $request->email)->update([
                    'email_verified_at' => now(),
                    'verification_code' => null,
                ]);
                $user->save();

                return response()->json([
                    'message' => "Verified successfully",
                ], 201);
            }
        } catch (\Exception $error) {
            return response()->json([
                'message' => "Verification Failed",
                'errors' => $error,
            ], 500);
        }
    }

        
    // Login 
    public function login(Request $request) {
        // $credentials = $request->only('email', 'password')
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8'
        ]);

        if (Auth::attempt($credentials)) {
            // $request->session()->regenerate();  // protecting against session fixation.
            $user = Auth::user()->fresh();
            $token = $user->createToken('login-token')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'login successfully!!',
                'user' => $user,
                'token' => $token,
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'Invalid Credentials',
        ], 400);
    }




    //    public function index()
    //     {
    //         return view('contact');
    //     }

      public function sendMail(Request $request)
{
    $request->validate([
        'name' => 'required',
        'email' => 'required|email',
        'subject' => 'required',
        'message' => 'required',
    ]);

    $data = [
        'name' => $request->name,
        'email' => $request->email,
        'subject' => $request->subject,
        'user_message' => $request->message,
    ];

    try {
        Mail::to('jeshrunlaw@gmail.com')->send(new ContactMail($data));
        return response()->json(['message' => 'Email sent successfully'], 200);
    } catch (\Exception $e) {
        // \Log::error('Contact mail failed: ' . $e->getMessage());
        return response()->json(['error' => 'Email sending failed.'], 500);
    }
}

}

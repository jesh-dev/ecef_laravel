<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\PasswordResetNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


class passwordResetController extends Controller
{
    //
public function forgottenPassword(Request $request)
{
    $request->validate(['email' => 'required|email|exists:users,email']);

    $token = Str::random(64);

    DB::table('password_reset_tokens')->updateOrInsert(
        ['email' => $request->email],
        ['token' => $token, 'created_at' => now()]
    );

    $user = User::where('email', $request->email)->first();
    $user->notify(new PasswordResetNotification($token));

    return response()->json([
        'message' => 'Reset link sent to your email.',
        'success' => true
    ], 200);
}


    
    // Handle password reset
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token'    => 'required',
            'email'    => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $reset = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->where('token', $request->token)
            ->first();

        if (!$reset || Carbon::parse($reset->created_at)->addMinutes(60)->isPast()) {
            return response()->json(['message' => 'Invalid or expired token'], 400);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Password has been reset successfully.',
        ]);
    }
}

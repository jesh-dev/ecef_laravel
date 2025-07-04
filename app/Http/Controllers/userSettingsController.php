<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class UserSettingsController extends Controller
{
    public function get(Request $request)
    {
        return response()->json($request->user());
    }

    public function update(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'phone_number' => 'nullable|string|max:20',
        ]);

        $user->firstname = $validated['firstname'];
        $user->lastname = $validated['lastname'];
        $user->phone_number = $validated['phone_number'] ?? null;
        $user->save();

        return response()->json(['message' => 'User settings updated successfully.', 'user' => $user]);
    }



public function changePassword(Request $request)
{
    $user = $request->user();

    $request->validate([
        'current_password' => ['required'],
        'new_password' => ['required', 'min:8', 'different:current_password'],
        'confirm_password' => ['required', 'same:new_password'],
    ]);

    if (!Hash::check($request->current_password, $user->password)) {
        throw ValidationException::withMessages([
            'current_password' => ['The current password is incorrect.'],
        ]);
    }

    $user->password = Hash::make($request->new_password);
    $user->save();

    return response()->json(['message' => 'Password updated successfully.']);
}


}

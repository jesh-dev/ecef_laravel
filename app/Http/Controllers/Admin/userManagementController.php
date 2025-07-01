<?php

namespace App\Http\Controllers\Admin;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class userManagementController extends Controller
{
    

    // Get only users with role = 'user'
public function allUsers(Request $request)
{
    $user = $request->user();

    if (!$user || $user->role !== 'admin') {
        return response()->json(['message' => 'Unauthorized'], 403);
    }

    $users = User::where('role', '!=', 'admin')
                 ->latest()
                 ->paginate(10); // 10 users per page

    return response()->json($users);
}





    public function show($id)
    {
        $user = User::find($id);
        if (!$user) return response()->json(['message' => 'User not found'], 404);
        return response()->json($user);
    }

    // public function update(Request $request, $id)
    // {
    //     $user = User::find($id);
    //     if (!$user) return response()->json(['message' => 'User not found'], 404);

    //     $user->update($request->only(['name', 'email', 'is_active'])); // customize as needed
    //     return response()->json(['message' => 'User updated successfully', 'user' => $user]);
    // }

    public function update(Request $request, $id)
{
    $user = User::find($id);

    if (!$user) {
        return response()->json(['message' => 'User not found'], 404);
    }

    $validated = $request->validate([
        'firstname' => 'required|string',
        'lastname' => 'required|string',
        'email' => 'required|email',
        'phone_number' => 'required|string',
    ]);

    $user->update($validated);

    return response()->json(['message' => 'User updated successfully', 'user' => $user]);
}


    public function destroy($id)
    {
        $user = User::find($id);
        if (!$user) return response()->json(['message' => 'User not found'], 404);

        $user->delete();
        return response()->json(['message' => 'User deleted successfully']);
    }
}

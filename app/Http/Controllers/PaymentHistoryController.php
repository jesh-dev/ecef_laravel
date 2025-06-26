<?php

namespace App\Http\Controllers;

use App\Models\PaymentHistory;
use Illuminate\Http\Request;

class PaymentHistoryController extends Controller
{
//     public function history()
// {
//     $user = auth()->user();

//     if (!$user) {
//         return response()->json(['message' => 'Unauthorized'], 401);
//     }

//     $histories = \App\Models\paymenthistory::whereHas('payment', function ($query) use ($user) {
//         $query->where('user_id', $user->id);
//     })->orderByDesc('timestamp')->get();

//     return response()->json($histories);
// }

}



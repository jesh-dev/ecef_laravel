<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;


class paymentController extends Controller
{
    //

    /*
    *Getting all payment for authenticated user with the name index.
    */
    public function index()
    {
        return auth()->user()
                     ->payments()
                     ->with('paymentHistory')
                     ->latest()
                     ->get();
    }

    /*
    *  Getting all payment with their history with the name show.
    */

    public function show($id)
    {
        $payment = \App\Models\Payment::with('paymentHistory')
        ->where('id', $id)
        ->where('user_id', auth()->id())
        ->firstOrFail();

        return response()->json($payment);
    }

    /**
     * Creating new payment with the name store...
     */
    public function store(Request $request) {

        $validator = Validator::make($request->all(),[
            'email' => 'email|required',
            'amount' => 'required|numeric|min:0',
            'pledge_amount' => 'numeric'

        ]);



        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
                'message' => 'invalid',
            ], 500);
        }

        try {
            $payment = new Payment;
            $payment->user_id = auth()->id();
            $payment->email = $request->email;
            $payment->amount = $request->amount;
            $payment->pledge_amount = $request->pledge_amount;
            $payment->save();
            // Mail::to($user->email)->send(new \App\Mail\UserEmailVerification($user));
            return response()->json([
                'payment' => $payment,
                'message' => 'Paid successfully',
            ], 201);

        } catch (\Exception $error) {
            return response()->json([
                'message' => 'Payment Failed',
                'error' => $error
            ], 400);
        }
    }
}

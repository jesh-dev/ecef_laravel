<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class paymentController extends Controller
{
    //
    public function payment(Request $request) {

        $validator = Validator::make($request->all(),[
            'fullname' => 'string',
            'email' => 'email',
            'amount' => 'numeric',
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
            $payment->fullname = $request->fullname;
            $payment->email = $request->email;
            $payment->amount = $request->amount;
            $payment->pledge_amount = $request->pledge_amount;
            $payment->save();

            return response()->json([
                'payment' => $payment,
                'message' => 'Payment done , thanks for your help',
            ], 200);

        } catch (\Exception $error) {
            return response()->json([
                'message' => 'Payment Failed',
                'error' => $error
            ], 400);
        }
    }
}

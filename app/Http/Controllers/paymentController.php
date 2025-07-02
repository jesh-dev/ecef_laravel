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
    *Getting all Paginated payment for authenticated user with the name index.
    */
//     public function index()
// {
//     $user = auth()->user();

//     $payments = $user->payments()->with('paymentHistory')->latest()->paginate(10);

//     return response()->json($payments);
// }
public function index(Request $request)
{
    $user = auth()->user();
    $perPage = $request->input('per_page', 5); // default to 5

    $payments = $user->payments()
        ->with('paymentHistory')
        ->latest()
        ->paginate($perPage);

    return response()->json($payments);
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
   public function store(Request $request)
{
    $validator = Validator::make($request->all(), [
        'email' => 'required|email',
        'amount' => 'required|numeric|min:0',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'errors' => $validator->errors(),
            'message' => 'Validation failed',
        ], 422); // 422 is more appropriate for validation errors
    }

    try {
        $payment = new Payment();
        $payment->user_id = auth()->id();
        $payment->email = auth()->user()->email;
        $payment->amount = $request->amount;
        $payment->save();

        return response()->json([
            'payment' => $payment,
            'message' => "Payment successful! Thank you for your donation.",
            'success' => true,
        ], 201);

    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Payment failed',
            'error' => $e->getMessage(),
            'success' => false,
        ], 500);
    }
}

}

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
public function paymentHistory(Request $request)
{
    $perPage = $request->input('per_page', 10); // default per page
    $payments = Payment::with('user') // eager load user info
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

    /**
     * Creating Overview for Payments with 5 recent payments.
     */
    public function overview()
{
    $user = auth()->user();

    $totalPayments = $user->payments()->count();
    $totalAmount   = $user->payments()->sum('amount');

    $recent = $user->payments()
        ->latest()
        ->take(5)
        ->with('user:id,firstname,lastname') // Eager load both parts of the name
        ->get(['id', 'amount', 'user_id'])
        ->map(function ($payment) {
            $first = $payment->user->firstname ?? '';
            $last  = $payment->user->lastname ?? '';
            $fullName = trim("$first $last") ?: 'Anonymous';

            return [
                'name'   => $fullName,
                'amount' => (float) $payment->amount,
            ];
        });

    return response()->json([
        'totalAmount' => (float) $totalAmount,
        'totalDonors' => $totalPayments,
        'recent'      => $recent,
    ]);
}


}

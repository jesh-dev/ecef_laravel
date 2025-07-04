<?php

namespace App\Observers;

use App\Models\Payment;
use App\Models\paymenthistory;
use Illuminate\Support\Facades\Auth;

class PaymentObserver
{
    /**
     * Handle the Payment "created" event.
     */
    public function created(Payment $payment): void
    {
         paymenthistory::create([
            'payment_id' => $payment->id,
            'user_id' => Auth::id(),
            'email' => $payment->email,
            'amount' => $payment->amount,
            'notes' => 'Payment created',
            'snapshot' => json_encode($payment->toArray()),
        ]);

    }

    /**
     * Handle the Payment "updated" event.
     */
    public function updated(Payment $payment): void
    {
        //
         if ($payment->isDirty([ 'amount', ])) {
            PaymentHistory::create([
                'payment_id'   => $payment->id,
                'user_id'      => Auth::id(),
                'amount'       => $payment->amount,
                'notes'        => 'Payment updated',
                'snapshot'     => $payment->toArray(),
            ]);
        }
    }

    /**
     * Handle the Payment "deleted" event.
     */
    public function deleted(Payment $payment): void
    {
        //
    }

    /**
     * Handle the Payment "restored" event.
     */
    public function restored(Payment $payment): void
    {
        //
    }

    /**
     * Handle the Payment "force deleted" event.
     */
    public function forceDeleted(Payment $payment): void
    {
        //
    }
}

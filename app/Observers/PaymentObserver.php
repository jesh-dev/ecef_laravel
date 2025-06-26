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
        //
        // paymenthistory::create([
        //     'payment_id' => $payment->id,
        //     'owner_by' => Auth::id(''), 
        //     'owner_type' => 'created',
        //     'amount' => $payment->amount,
        //     'email' => $payment->email,
        //     'note' => 'payment created',
        //     'snapshot' => $payment->toArray(),
        // ]);
         PaymentHistory::create([
            'payment_id' => $payment->id,
            'owner_by' => Auth::id(''),
            'email' => $payment->email,
            'amount' => $payment->amount,
            'pledge_amount' => $payment->pledge_amount,
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
         if ($payment->isDirty(['status', 'amount', 'currency'])) {
            PaymentHistory::create([
                'payment_id'   => $payment->id,
                'owner_by'   => Auth::id(),
                'owner_type'  => 'updated',
                'new_status'   => $payment->status,
                'amount'       => $payment->amount,
                'currency'     => $payment->currency,
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

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class paymenthistory extends Model
{
    //
    use HasFactory;

    protected $table = 'payment_histories';

    protected $fillable = [
        'payment_id',
        'amount',
        'email',
        'pledge_amount',
        'timestamp',
        'notes',
        'snapshot',
    ];

    protected $casts = [
        'snapshot' => 'array',
        'timestamp' => 'datetime',
    ];

    public $timestamps = false; // using 'timestamp' instead

    // // Sharing relationship with payment snd User.
    public function payment()
    {
        return $this->belongsTo(payment::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'owner_by');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    //  
    protected $fillable = [
        'user_id',
        "email",
        'amount',
    ] ;


      /**
       * sharing relationships with user and history.
       */

       public function users()
       {
        return $this->belongsTo(\App\Models\User::class);
       }

       public function paymentHistory()
       {
        return $this->hasMany(\App\Models\PaymentHistory::class);
       }

    
}
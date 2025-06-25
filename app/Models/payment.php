<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class payment extends Model
{
    use HasFactory;
    //  
    protected $fillable = [
        'user_id',
        "email",
        'amount',
        'pledge_amount',
    ] ;


      /**
       * sharing relationships with user and history.
       */

       public function user()
       {
        return $this->belongsTo(User::class);
       }

       public function paymentHistory()
       {
        return $this->hasMany(paymentHistory::class);
       }

    
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class payment extends Model
{
    //  
    protected $fillable = [
        "email",
        'amount',
        'pledge_amount',
    ] ;
}

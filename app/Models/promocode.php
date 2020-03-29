<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class promocode extends Model
{
    //

    protected $table = 'promocode';
    protected $fillable = [
        
        'user_id', 
        'code',
        'description',
        'type',
        'amount',
        'minOrderAmount',
        'maxDiscountAmount',
        'created_at',
        'updated_at'
    ];
}

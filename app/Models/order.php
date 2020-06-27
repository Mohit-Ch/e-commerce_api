<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class order extends Model
{
    //
    protected $table = 'order';
    protected $fillable = [
        
        'order_no', 
        'user_id',
        'promocode_id',
        'description' ,
        'type',
        'status',
        'product_amount',
        'Discount',
        'actual_amount',
        'address_id',
        'created_at',
        'updated_at',
        'deviceId',
        'name',
        'email',
        'phone_no',
        'company_name',
        'address'
        
    ];
}

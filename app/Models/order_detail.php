<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class order_detail extends Model
{
    //

    protected $table = 'order_detail';
    protected $fillable = [
        
        'order_id', 
        'itemedition_id',
        'created_at',
        'updated_at',
        'quantity',
        'price'
    ];
}

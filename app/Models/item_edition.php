<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class item_edition extends Model
{
    //
    protected $table = 'item_edition';
    protected $fillable = [
        
        'itemDetail_id', 
        'itemEditionName',
        'price',
        'quantity' ,
        'remark',
        'status',
        'created_at',
        'updated_at'
    ];
}

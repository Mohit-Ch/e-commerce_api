<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class item_details_attributes extends Model
{
    //
    protected $table = 'item_details_attributes';
    protected $fillable = [
        
        'itemDetail_id', 
        'AttributeKey',
        'type',
        'created_at' ,
        'updated_at' 
    ];
}

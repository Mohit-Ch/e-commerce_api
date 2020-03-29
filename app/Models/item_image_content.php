<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class item_image_content extends Model
{
    //

    protected $table = 'item_image_content';
    protected $fillable = [
        
        'itemDetail_id', 
        'description',
        'imageURL',
        'size' ,
        'type',
        'created_at',
        'updated_at'
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class item_deatil extends Model
{
    //

    protected $table = 'item_deatil';
    protected $fillable = [
        
        'itemName', 
        'aboutItem',
        'category_id',
        'subcategory_id' ,
        'user_id' ,
        'width',
        'height',
        'length',
        'weight',
        'dimensionUnit',
        'wtUnit',
        'created_at',
        'updated_at'
    ];
}

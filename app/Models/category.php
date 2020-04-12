<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class category extends Model
{
    //catrgory
    protected $table = 'category';
    protected $fillable = [
        
        'category_name', 
        'photo',
        'is_deleted'       
    ];
}

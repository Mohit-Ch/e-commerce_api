<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class subcategory extends Model
{
    //

    protected $table = 'subcategory';
    protected $fillable = [
        
        'category_name', 
        'photo',
        'category_id',
        'created_at',
        'updated_at',
        'is_deleted'
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class guest_user extends Model
{
    //
    protected $table = 'error_logs';
    protected $fillable = [
        
        'err_text', 
        'file_path',
        'method',
        'parent_method' ,
        'error_time' ,
        'type'  
    ];
}

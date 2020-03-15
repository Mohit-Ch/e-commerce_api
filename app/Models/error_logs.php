<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class error_logs extends Model
{
    // errorlog
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

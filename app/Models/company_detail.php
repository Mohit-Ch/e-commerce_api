<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class company_detail extends Model
{
    //
    protected $table = 'company_detail';
    protected $fillable = [
        
        'Key', 
        'value'      
    ];
}

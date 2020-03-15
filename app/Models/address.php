<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class address extends Model
{
    // address table
    protected $table = 'address';
    protected $fillable = [
        
        'user_id', 
        'address1',
        'address2',
        'country','city','area','postal_code','is_default'
       
    ];
}

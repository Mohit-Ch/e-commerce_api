<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class address extends Model
{
    // address table
    protected $table = 'address';
    protected $fillable = [
        
        'users_id', 
        'address1',
        'address2',
        'country','city','area','postal_code','is_default','name','email','phone_no','company_name'
       
    ];
}

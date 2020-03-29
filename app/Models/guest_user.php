<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class guest_user extends Model
{
    //
    protected $table = 'guest_user';
    protected $fillable = [
        
        'name', 
        'company_name',
        'email',
        'phone_no' ,
        'address' ,
        'created_at',
        'updated_at'
    ];
}

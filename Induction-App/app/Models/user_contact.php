<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\City;

class user_contact extends Model
{
    //
    protected $fillable = [
        'user_id',
        'phone',
        'mobile',
        'permanent_address',
        'postal_address',
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function city()
    {
        return $this->belongsTo(cities::class, 'city');   
    }
}

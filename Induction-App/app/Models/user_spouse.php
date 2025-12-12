<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class user_spouse extends Model
{
    //

    
    protected $fillable = [
        'user_id',
        'spouse_name',
        'spouse_cnic',
        'city_id',
        'useHusbandDomicile',
    ];
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function city(): BelongsTo
    {
        return $this->belongsTo(cities::class, 'city_id');
    }
}

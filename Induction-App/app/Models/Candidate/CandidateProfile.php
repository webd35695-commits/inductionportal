<?php

namespace App\Models\Candidate;

use App\Models\cities;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CandidateProfile extends Model
{
    //
    protected $fillable = [
        'user_id',
        'name',
        'father_name',
        'gender',
        'marital_status',
        'city_id',
        'date_of_birth',
        'disability',
        'religion',
        'cnic',
        'photo_path',
    ];

   public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function city()
    {
        return $this->belongsTo(cities::class, 'city_id');
    }
    

}

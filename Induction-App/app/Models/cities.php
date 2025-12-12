<?php

namespace App\Models;

use App\Models\Candidate\CandidateProfile;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class cities extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'district_id',  'created_by', 'updated_by'];

    /**
     * Get the district that owns the city
     */
    public function district(): BelongsTo
    {
        return $this->belongsTo(district::class);
    }

    
    /**
     * Get the candidates associated with the city
     */
    public function candidates()
    {
        return $this->hasOne(CandidateProfile::class);
    }
    public function AppliedPosts(){
                return $this->hasMany(AppliedPosts::class, 'preferred_city_id');
    }
     public function centers(): HasMany
    {
        return $this->hasMany(center::class, 'city_id');
    }
    
}

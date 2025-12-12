<?php

namespace App\Models;

use App\Models\Candidate\CandidateProfile;
use App\Models\Candidate\qualification;
use App\Models\City;
use App\Models\District;
use App\Models\Province;

use App\Models\AgeRelaxtion;
use App\Models\user_contact;
use App\Models\user_spouse;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, HasRoles; // Removed duplicate HasRoles

    protected $fillable = [
        'name',
        'email',
        'password',
        'email_verified_at'
    ];
    protected $hidden = [
        'password',
        'remember_token',
    ];
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function userSpouses(): HasOne
    {
        return $this->hasOne(user_spouse::class, 'user_id');
    }
     public function userContact(): HasOne
    {
        return $this->hasOne(user_contact::class, 'user_id');
    }
    public function candidateProfile(): HasOne
    {
        return $this->hasOne(CandidateProfile::class, 'user_id');
    }
    public function qualifications(): HasMany
    {
        return $this->hasMany(Qualification::class, 'user_id', 'id');
    }
    public function ageRelaxation()
    {
        return $this->hasOne(AgeRelaxation::class, 'user_id');
    }
     public function AppliedPosts()
    {
        return $this->hasMany(AppliedPosts::class, 'user_id');
    }


}

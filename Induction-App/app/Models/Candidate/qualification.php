<?php

namespace App\Models\Candidate;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class qualification extends Model
{
    protected $table = 'qualifications';
    protected $fillable = [
        'user_id',
        'degree_type_id',
        'degree_name',
        'institute_name',
        'passing_year',
        'status',
        'created_at',
        'updated_at',
    ];
    //
    public function degreeType()
    {
        return $this->belongsTo('App\Models\QualificationType', 'degree_type_id');
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

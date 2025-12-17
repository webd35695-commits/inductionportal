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
        'grade',
        'cgpa',
        'qualification_category_id',
        'status',
        'created_at',
        'updated_at',
    ];
    //
    public function degreeType()
    {
        return $this->belongsTo(\App\Models\QualificationType::class, 'degree_type_id');
    }

    public function qualificationCategory()
    {
        return $this->belongsTo(\App\Models\QualificationCategory::class, 'qualification_category_id');
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

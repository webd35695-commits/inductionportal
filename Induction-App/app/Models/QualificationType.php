<?php

namespace App\Models;

use App\Models\Candidate\qualification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QualificationType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'status',
    ];

    public function posts()
    {
        return $this->hasMany(Post::class);
    }
    public function qualifications()
    {
        return $this->hasMany(qualification::class, 'degree_type_id');
    }

}

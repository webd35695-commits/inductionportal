<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QualificationCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'qualification_type_id',
        'name',
        'status',
    ];

    public function qualificationType()
    {
        return $this->belongsTo(QualificationType::class);
    }
}

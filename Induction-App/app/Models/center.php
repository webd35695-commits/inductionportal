<?php

namespace App\Models;

use App\Models\cities;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class center extends Model
{
 use HasFactory;
    protected $fillable = [
        'city_id',
        'name',
        'address',
        'contact_number',
        'email',
        'latitude',
        'longitude',
        'capacity',
        'is_active',
        'user_id',
        'created_by',
        'updated_by',
        'status',
    ];

    public function cities()
    {
        return $this->belongsTo(cities::class, 'city_id');
    }
    
    public function posts()
    {
        return $this->belongsToMany(Post::class, 'center_posts', 'center_id', 'post_id')
                    ->withTimestamps()
                    ->withPivot(['max_applicants', 'exam_schedule_id', 'assigned_by', 'id'])
                    ->select(['posts.id', 'posts.name', 'posts.post_abbreviation']);
    }

    /**
     * Get assigned posts with schedule details.
     */
    public function postsWithSchedule()
    {
        return $this->belongsToMany(Post::class, 'center_posts', 'center_id', 'post_id')
                    ->withTimestamps()
                    ->withPivot(['max_applicants', 'exam_schedule_id', 'assigned_by', 'id'])
                    ->with('examSchedules');
    }

    /**
     * Get all allotments for this center.
     */
    public function allotments()
    {
        return $this->hasMany(CentersAllotment::class, 'center_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CenterPost extends Model
{
    protected $table = 'center_posts';

    protected $fillable = [
        'center_id',
        'post_id',
        'exam_schedule_id',
        'max_applicants',
        'assigned_by',
    ];

    protected $casts = [
        'max_applicants' => 'integer',
    ];

    /**
     * Get the center that owns this assignment.
     */
    public function center(): BelongsTo
    {
        return $this->belongsTo(center::class);
    }

    /**
     * Get the post that is assigned.
     */
    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    /**
     * Get the exam schedule for this assignment.
     */
    public function examSchedule(): BelongsTo
    {
        return $this->belongsTo(ExamSchedule::class);
    }

    /**
     * Get the user who made this assignment.
     */
    public function assignedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }
}
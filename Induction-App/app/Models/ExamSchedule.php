<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class ExamSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'post_id',
        'exam_date',
        'start_time',
        'end_time',
        'duration_minutes',
        'is_active',
    ];

    protected $casts = [
        'exam_date' => 'date',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'is_active' => 'boolean',
        'duration_minutes' => 'integer',
    ];

    /**
     * Get the post that owns this exam schedule.
     */
    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    /**
     * Get all center posts using this schedule.
     */
    public function centerPosts(): HasMany
    {
        return $this->hasMany(CenterPost::class);
    }

    /**
     * Get formatted time display.
     */
    public function getFormattedTimeAttribute(): string
    {
        $start = Carbon::parse($this->start_time)->format('h:i A');
        $end = Carbon::parse($this->end_time)->format('h:i A');
        return "{$start} - {$end}";
    }

    /**
     * Get formatted date display.
     */
    public function getFormattedDateAttribute(): string
    {
        return Carbon::parse($this->exam_date)->format('M d, Y');
    }

    /**
     * Scope to get only active schedules.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get schedules for a specific post.
     */
    public function scopeForPost($query, $postId)
    {
        return $query->where('post_id', $postId);
    }
}

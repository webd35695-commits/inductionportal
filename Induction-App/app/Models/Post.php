<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'induction_phase_id',
        'post_category_id',
        'qualification_type_id',
        'name',
        'number_post',
        'fee_post',
        'min_age',
        'max_age',
        'post_abbreviation',
        'post_gender',
        'bps',
        'requirements',
        'degree_required',
    ];

    protected $casts = [
        'degree_required' => 'boolean',
        'bps' => 'integer',
        'fee_post' => 'decimal:2',
    ];

 // In Post.php
public function scopeEligibleWithRelaxation($query, array $eligibilityData)
{
    $effectiveAge = $eligibilityData['effective_age'];
    $currentAge = $eligibilityData['current_age'];

    return $query->where('min_age', '<=', $effectiveAge)
                ->where('max_age', '>=', $currentAge);
}

public function scopeEligibleForUser($query, $user_details, $age_relaxation = null)
{
    if (!$user_details || !$user_details->date_of_birth || !$user_details->gender) {
        return $query; // Return unfiltered query if user_details or required fields are missing
    }
    $base_age = Carbon::parse($user_details->date_of_birth)->age;
    $relaxed_age = $base_age;
    // Calculate age relaxation

    if ($age_relaxation) {
        $total_relaxation_years = 0;
        $total_relaxation_months = 0;
        $total_relaxation_days = 0;

        // 1. Schedule Caste - 3 years relaxation
        if ($age_relaxation->relax_schedule_caste) {
            $total_relaxation_years += 3;
        }


        // 2. Disability - 10 years relaxation (corrected from PHP code)
        if ($age_relaxation->relax_disable) {
            $total_relaxation_years += 10;
        }

        // 3. Widow/Widower - 5 years relaxation
        if ($age_relaxation->relax_widow) {
            $total_relaxation_years += 5;
        }

        // 3. Government Employee - 10 years if service > 2 years
        if ($age_relaxation->gov && $age_relaxation->gov_appoint_date && $age_relaxation->gov_retire_date) {
            $appoint_date = Carbon::parse($age_relaxation->gov_appoint_date);
            $retire_date = Carbon::parse($age_relaxation->gov_retire_date);
            $service_years = $retire_date->diffInYears($appoint_date);

            if ($service_years >= 2) {
                $total_relaxation_years += 10;
            }
        }

        // 4. Armed Forces - Complex calculation based on service period
        if ($age_relaxation->relax_retired && $age_relaxation->relax_retired_appoint && $age_relaxation->relax_retired_retired) {
            $appoint_date = Carbon::parse($age_relaxation->relax_retired_appoint);
            $retire_date = Carbon::parse($age_relaxation->relax_retired_retired);

            $service_years = $retire_date->diffInYears($appoint_date);
            $total_service_days = $retire_date->diffInDays($appoint_date);

            if ($service_years >= 15) {
                // 15 years relaxation for 15+ years service
                $total_relaxation_years += 15;
            } else {
                // Total days of service as relaxation for less than 15 years
                // Convert total days to years (approximately)
                $total_relaxation_years += ($total_service_days / 365.25);
            }
        }

        // Convert everything to a decimal age for comparison
        $relaxed_age = $base_age + $total_relaxation_years + ($total_relaxation_months / 12) + ($total_relaxation_days / 365);
    }

    return $query->where(function ($q) use ($user_details) {
        $q->where('post_gender', $user_details->gender)
          ->orWhere('post_gender', 'both');
    })
    ->where('min_age', '<=', $relaxed_age)
    ->where('max_age', '>=', $base_age); // Still use base age for minimum requirement
}

    public function inductionPhase(): BelongsTo
    {
        return $this->belongsTo(InductionPhase::class, 'induction_phase_id');
    }
    public function quotaSetting(): HasMany
    {
        return $this->hasMany(QuotaSetting::class, 'post_id');
    }


    public function category(): BelongsTo
    {
        return $this->belongsTo(post_category::class, 'post_category_id');
    }

    public function qualificationType(): BelongsTo
    {
        return $this->belongsTo(QualificationType::class, 'qualification_type_id');
    }
    public function AppliedPosts()
    {
        return $this->hasMany(AppliedPosts::class, 'post_id');
    }

    /**
     * Get all exam schedules for this post.
     */
    public function examSchedules()
    {
        return $this->hasMany(ExamSchedule::class);
    }

    /**
     * Get the current active exam schedule for this post.
     */
    public function activeSchedule()
    {
        return $this->hasOne(ExamSchedule::class)->where('is_active', true)->latest();
    }

    /**
     * Get centers where this post is assigned.
     */
    public function centers()
    {
        return $this->belongsToMany(center::class, 'center_posts', 'post_id', 'center_id')
                    ->withTimestamps()
                    ->withPivot(['max_applicants', 'exam_schedule_id', 'assigned_by', 'id']);
    }

    /**
     * Get all allotments for this post (through applied_posts).
     */
    public function allotments()
    {
        return $this->hasManyThrough(
            CentersAllotment::class,
            AppliedPosts::class,
            'post_id', // Foreign key on applied_posts
            'applied_post_id', // Foreign key on centers_allotments
            'id', // Local key on posts
            'id' // Local key on applied_posts
        );
    }
}

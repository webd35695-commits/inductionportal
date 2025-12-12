<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class AppliedPosts extends Model
{
    //
    protected $table="applied_posts";
    protected $fillable = [
        'user_id',
        'post_id',
        'preferred_city_id',
        'alternative_city_id',
        'status',
        'rejection_reason',
        'applied_at',
        'created_at',
        'updated_at',
    ];



    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function post()
    {
        return $this->belongsTo(Post::class, 'post_id');
    }
    public function cities()
    {
        return $this->belongsTo(cities::class, 'post_id');
    }

    public function preferredCity(): BelongsTo
    {
        return $this->belongsTo(cities::class, 'preferred_city_id');
    }

    // Relationship to alternative city (if exists)
    public function alternativeCity(): BelongsTo
    {
        return $this->belongsTo(cities::class, 'alternative_city_id');
    }

     // Relationship to allotment
    public function allotment(): HasOne
    {
        return $this->hasOne(CentersAllotment::class, 'applied_post_id');
    }

    public function scopeApproved(Builder $query): Builder
    {
        return $query->where('status', 'approved');
    }

    public function scopeWithoutAllotment(Builder $query): Builder
    {
        return $query->whereDoesntHave('allotment');
    }

    public function scopeWithRelations(Builder $query): Builder
    {
        return $query->with(['user.candidateProfile', 'post', 'preferredCity', 'allotment.center']);
    }

    public function scopeFilterByPost(Builder $query, $postId): Builder
    {
        return $postId ? $query->where('post_id', $postId) : $query;
    }

    public function scopeFilterByCity(Builder $query, $cityId): Builder
    {
        return $cityId ? $query->where('preferred_city_id', $cityId) : $query;
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'applied_post_id');
    }

    public function latestPayment()
    {
        return $this->hasOne(Payment::class, 'applied_post_id')->latestOfMany();
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CentersAllotment extends Model
{
    use HasFactory;

    protected $table = 'centers_allotments';

    protected $fillable = [
        'user_id',
        'applied_post_id',
        'center_id',
        'roll_number',
        'status',
    ];

    protected $casts = [
        'allotted_at' => 'datetime',
    ];

    const STATUS_PENDING = 'pending';
    const STATUS_ALLOTTED = 'allotted';
    const STATUS_REJECTED = 'rejected';

    public static $statuses = [
        self::STATUS_PENDING => 'Pending',
        self::STATUS_ALLOTTED => 'Allotted',
        self::STATUS_REJECTED => 'Rejected',
    ];

    /**
     * Get the user associated with the allotment.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the applied post associated with the allotment.
     */
    public function appliedPost(): BelongsTo
    {
        return $this->belongsTo(AppliedPosts::class);
    }

    /**
     * Get the center associated with the allotment.
     */
    public function center(): BelongsTo
    {
        return $this->belongsTo(Center::class);
    }

    /**
     * Scope a query to only include allotted centers.
     */
    public function scopeAllotted($query)
    {
        return $query->where('status', self::STATUS_ALLOTTED);
    }

    /**
     * Scope a query to only include pending centers.
     */
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    /**
     * Check if the allotment is pending.
     */
    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    /**
     * Check if the allotment is allotted.
     */
    public function isAllotted(): bool
    {
        return $this->status === self::STATUS_ALLOTTED;
    }

    /**
     * Check if the allotment is rejected.
     */
    public function isRejected(): bool
    {
        return $this->status === self::STATUS_REJECTED;
    }
}
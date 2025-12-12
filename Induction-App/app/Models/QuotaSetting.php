<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuotaSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'induction_phase_id',
        'post_id',
        'province_id',
        'open_merit',      // Not province specific
        'merit',           // Province specific merit
        'women',
        'minority',
        'special_persons',
    ];

    protected $casts = [
        'open_merit' => 'integer',
        'merit' => 'integer',
        'women' => 'integer',
        'minority' => 'integer',
        'special_persons' => 'integer',
    ];

    public function inductionPhase(): BelongsTo
    {
        return $this->belongsTo(InductionPhase::class);
    }

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    public function province(): BelongsTo
    {
        return $this->belongsTo(Province::class);
    }

    // Get total quota for province-specific allocations only
    public function getTotalProvinceQuotaAttribute(): int
    {
        return $this->merit + $this->women + $this->minority + $this->special_persons;
    }

    // Get total quota including open merit (only for open merit records)
    public function getTotalQuotaAttribute(): int
    {
        return $this->open_merit + $this->merit + $this->women + $this->minority + $this->special_persons;
    }

    public function getQuotaBreakdownAttribute(): array
    {
        return [
            'open_merit' => $this->open_merit,
            'merit' => $this->merit,
            'women' => $this->women,
            'minority' => $this->minority,
            'special_persons' => $this->special_persons,
            'total_province_quota' => $this->total_province_quota,
            'total_quota' => $this->total_quota
        ];
    }

    // Scope to get open merit records (where province_id is null)
    public function scopeOpenMerit($query)
    {
        return $query->whereNull('province_id');
    }

    // Scope to get province-specific records
    public function scopeProvinceSpecific($query)
    {
        return $query->whereNotNull('province_id');
    }
}

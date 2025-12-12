<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AgeRelaxation extends Model
{
    protected $fillable = [
        'user_id',
        'relax_schedule_caste',
        'relax_retired',
        'relax_retired_from',
        'relax_retired_position',
        'relax_retired_appoint',
        'relax_retired_retired',
        'relax_disable',
        'relax_disabled_nature',
        'relax_widow',
        'relax_name_employ',
        'relax_designation',
        'relax_department',
        'relax_date_death',
        'gov',
        'gov_name',
        'gov_designation',
        'gov_basic_pay',
        'gov_appoint_date',
        'gov_retire_date',
        'gov_appoint_nature',
    ];

    /**
     * Get the user that owns the age relaxation record.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
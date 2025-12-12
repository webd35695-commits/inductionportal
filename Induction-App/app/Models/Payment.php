<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $fillable = [
        'applied_post_id', 'amount', 'status', 'provider', 'reference_no', 'transaction_id', 'meta', 'paid_at'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'meta' => 'array',
        'paid_at' => 'datetime',
    ];

    public function appliedPost(): BelongsTo
    {
        return $this->belongsTo(AppliedPosts::class, 'applied_post_id');
    }
}

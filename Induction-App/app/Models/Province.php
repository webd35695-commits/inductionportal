<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Province extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'status'];

    /**
     * Get all districts for the province
     */
    public function districts(): HasMany
    {
        return $this->hasMany(District::class);
    }
    
}

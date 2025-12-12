<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Types\Model\Posts;

class InductionPhase extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'status'];

    public function Posts():HasMany
    {
        return $this->hasMany(Post::class);
    }
}

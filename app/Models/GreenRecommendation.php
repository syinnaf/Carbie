<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GreenRecommendation extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 
        'description', 
        'category_id', 
        'carbon_threshold', 
        'priority', 
        'is_active'
    ];

    protected $casts = [
        'carbon_threshold' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(ActivityCategory::class);
    }
}

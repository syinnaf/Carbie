<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarbonActivity extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id', 
        'activity_type',
        'activity_name',
        'unit', 
        'carbon_factor', 
        'description',
        'sub_category'
    ];

    protected $casts = [
        'carbon_factor' => 'decimal:6',
    ];

    public function category()
    {
        return $this->belongsTo(ActivityCategory::class);
    }

    public function calculateCarbon($quantity)
    {
        return $this->carbon_factor * $quantity;
    }

    // Scope untuk mencari aktivitas berdasarkan kategori dan tipe
    public function scopeByCategory($query, $categorySlug)
    {
        return $query->whereHas('category', function($q) use ($categorySlug) {
            $q->where('slug', $categorySlug);
        });
    }

    public function scopeByActivityType($query, $activityType)
    {
        return $query->where('activity_type', $activityType);
    }

    public function scopeBySubCategory($query, $subCategory)
    {
        return $query->where('sub_category', $subCategory);
    }
}
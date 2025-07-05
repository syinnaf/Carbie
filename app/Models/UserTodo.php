<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserTodo extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'description',
        'details',
        'completed',
        'date'
    ];

    protected $casts = [
        'details' => 'array',
        'completed' => 'boolean',
        'date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(ActivityCategory::class);
    }

    public function carbonLogs()
    {
        return $this->hasMany(UserCarbonLog::class, 'todo_id');
    }

    // Scope untuk filter berdasarkan tanggal
    public function scopeByDate($query, $date)
    {
        return $query->where('date', $date);
    }

    // Scope untuk filter berdasarkan kategori
    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    // Scope untuk filter berdasarkan status completed
    public function scopeCompleted($query)
    {
        return $query->where('completed', true);
    }

    public function scopeIncomplete($query)
    {
        return $query->where('completed', false);
    }

    // Accessor untuk total carbon
    public function getTotalCarbonAttribute()
    {
        return $this->carbonLogs->sum('carbon_amount');
    }

    // Method untuk menghitung carbon berdasarkan details
    public function calculateCarbon()
    {
        if (!$this->category || empty($this->details)) {
            return 0;
        }

        $carbonCalculatorService = app(\App\Services\CarbonCalculatorService::class);
        return $carbonCalculatorService->calculateCarbon($this->category->slug, $this->details);
    }
}
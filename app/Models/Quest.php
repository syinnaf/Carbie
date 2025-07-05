<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quest extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 
        'description', 
        'target_value', 
        'type', 
        'exp_reward', 
        'is_active'
    ];

    protected $casts = [
        'target_value' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function userQuests()
    {
        return $this->hasMany(UserQuest::class);
    }
}

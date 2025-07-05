<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'email', 
        'nickname', 
        'password', 
        'level', 
        'exp', 
        'daily_carbon_limit'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'daily_carbon_limit' => 'decimal:2',
    ];

    public function todos()
    {
        return $this->hasMany(UserTodo::class);
    }

    public function carbonLogs()
    {
        return $this->hasMany(UserCarbonLog::class);
    }

    public function quests()
    {
        return $this->hasMany(UserQuest::class);
    }

    public function addExperience(int $exp)
    {
        $this->exp += $exp;
        $this->checkLevelUp();
        $this->save();
    }

    private function checkLevelUp()
    {
        $requiredExp = $this->level * 200; // 100 exp per level ganti 200
        if ($this->exp >= $requiredExp) {
            $this->level++;
            $this->exp -= $requiredExp;
            $this->checkLevelUp(); // Check for multiple level ups
        }
    }
}

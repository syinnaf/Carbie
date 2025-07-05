<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class UserQuest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 
        'quest_id', 
        'status', 
        'progress', 
        'started_at', 
        'completed_at'
    ];

    protected $casts = [
        'progress' => 'decimal:2',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function quest()
    {
        return $this->belongsTo(Quest::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserCarbonLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 
        'session_id', 
        'todo_id', 
        'date', 
        'carbon_amount', 
        'activity_type', 
        'calculation_details'
    ];

    protected $casts = [
        'date' => 'date',
        'carbon_amount' => 'decimal:2',
        'calculation_details' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function todo()
    {
        return $this->belongsTo(UserTodo::class);
    }
}

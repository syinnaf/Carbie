<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityCategory extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'description'];

    public function carbonActivities()
    {
        return $this->hasMany(CarbonActivity::class, 'category_id');
    }

    public function todos()
    {
        return $this->hasMany(UserTodo::class, 'category_id');
    }
}
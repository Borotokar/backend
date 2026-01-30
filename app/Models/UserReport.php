<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserReport extends Model
{
    use HasFactory;
    protected $fillable = ['expert_id', 'user_id', 'violation_type', 'description', 'status'];

    // ارتباط با متخصص
    public function expert()
    {
        return $this->belongsTo(expert::class, 'expert_id');
    }

    // ارتباط با کاربر
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

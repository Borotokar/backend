<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blocking extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'expert_id', 'block_type'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function expert()
    {
        return $this->belongsTo(expert::class);
    }
}

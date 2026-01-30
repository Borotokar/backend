<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpertVerifications extends Model
{
    use HasFactory;

    protected $fillable = ['expert_id', 'video_path',];

    public function expert()
    {
        return $this->belongsTo(expert::class);
    }
}

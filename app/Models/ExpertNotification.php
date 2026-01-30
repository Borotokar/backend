<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpertNotification extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'message', 'expert_id', 'seen'];

    protected $casts = [
	'seen' => 'boolean',
    ];
}

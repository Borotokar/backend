<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserNotification extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'message', 'user_id', 'seen'];

    protected $casts = [
	'seen' => 'boolean',
    ];
}

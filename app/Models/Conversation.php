<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Conversation extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['user_id', 'expert_id', 'seen', 'expert_seen'];

    protected $casts = [
	    'seen' => 'boolean',
	    'expert_seen' => 'boolean',
    ];

    protected $appends = ['user_block', 'expert_block'];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function expert()
    {
	return $this->belongsTo(expert::class);
    }


    public function messages()
    {
        return $this->hasMany(Message::class);
    }


    public function getUserBlockAttribute()
    {
        return Blocking::where('expert_id', $this->expert_id)
            ->where('user_id', $this->user_id)
            ->where('block_type', 'user') // یا نوع بلاک مورد نظر
            ->exists();
    }

    public function getExpertBlockAttribute()
    {
        return Blocking::where('expert_id', $this->expert_id)
            ->where('user_id', $this->user_id)
            ->where('block_type', 'expert') // یا نوع بلاک مورد نظر
            ->exists();
    }
}

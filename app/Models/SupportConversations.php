<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupportConversations extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'expert_id'];
    protected $appends = ['is_read'];

    public function messages()
    {
        return $this->hasMany(SupportMessages::class, 'conversation_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function expert()
    {
        return $this->belongsTo(expert::class);
    }

    public function latestMessage()
    {
        return $this->hasOne(SupportMessages::class, 'conversation_id')->latestOfMany();
    }

    public function getIsReadAttribute()
    {
        return !$this->messages()->where('is_read', false)->exists();
    }
}

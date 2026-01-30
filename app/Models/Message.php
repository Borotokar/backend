<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
	'conversation_id',    
	'message',
        'sender_id',
        'sender_type',
    ];
    
    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

        public function expert()
    {
        return $this->belongsTo(expert::class);
    }

}

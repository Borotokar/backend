<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupportMessages extends Model
{
    use HasFactory;
    protected $fillable = ['conversation_id', 'sender_type', 'sender_id', 'message', 'is_read'];

    public function conversation()
    {
        return $this->belongsTo(SupportConversation::class, 'conversation_id');
    }
}

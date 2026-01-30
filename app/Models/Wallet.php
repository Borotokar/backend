<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    use HasFactory;
    protected $fillable = ['expert_id', 'balance'];

    public function expert()
    {
        return $this->belongsTo(expert::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpertDocuments extends Model
{
    use HasFactory;

    protected $fillable = [
        'expert_id', 'type', 'path',
    ];

    public $timestamps = false;

    public function expert()
    {
        return $this->belongsTo(expert::class);
    }
}

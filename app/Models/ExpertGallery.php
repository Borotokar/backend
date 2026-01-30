<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Notifiable;

class ExpertGallery extends Model
{
    use HasFactory,Notifiable;

    protected $fillable = [
        'expert_id', 'path',
    ];

    public $timestamps = false;

    public function expert()
    {
        return $this->belongsTo(expert::class);
    }
}

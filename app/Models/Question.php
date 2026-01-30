<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = ['service_id', 'type', 'question'];

    public function service(){
        return $this->belongsTo(Service::class);
    }

    public function answer(){
        return $this->hasMany(Answer::class);
    }

    public function predefinedAnswer(){
        return $this->hasMany(PredefinedAnswer::class);
    }
}

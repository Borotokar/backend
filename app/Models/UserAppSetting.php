<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAppSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'baneer1',
        'baneer2',
        'baneer3',
        'baneer4',
        'expert_id1',
        'expert_id2',
        'expert_id3',
        'expert_id4',
	    'law',
	    'categories'
    ];

    public function expert1()
    {
        return $this->belongsTo(expert::class, 'expert_id1');
    }

    public function expert2()
    {
        return $this->belongsTo(expert::class, 'expert_id2');
    }

    public function expert3()
    {
        return $this->belongsTo(expert::class, 'expert_id3');
    }

    public function expert4()
    {
        return $this->belongsTo(expert::class, 'expert_id4');
    }
}

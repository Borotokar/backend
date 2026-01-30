<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Notifiable;

class Order extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'user_id',
        'service_id',
        'description',
        'address',
        'city',
        'status',
        'lat',
        'log',
        'completion_date',
        'completion_time',
        'notified_at'
    ];

    /*
        status 1 درحال پردازش
        status 2 مشهاده پینهاد
        status 3 درحال انجام
        status 4 انجام شده
        status 5 لغو شده
    */

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    protected $appends = ['unseen_bids_count'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function review()
    {
        return $this->hasMany(Review::class)->where('is_active', true);
    }

    public function userReview()
    {
        return $this->hasMany(Review::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function bids()
    {
        return $this->hasMany(Bid::class)->where('is_active', true);
    }

    // public function bid($expert_id)
    // {
    //     return $this->hasMany(Bid::class)->where('expert_id', $expert_id);
    // }

    public function answers()
    {
    return $this->hasMany(Answer::class);
    }

    public function getUnseenBidsCountAttribute()
    {
        return $this->bids()->where('is_seen_by_user', false)->count();
    }

}

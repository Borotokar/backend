<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
//use Illuminate\Notifications\Notifiable;
use App\Traits\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;

class expert extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $guard = 'expert';

    protected $fillable = [
        'phone_number', 'first_name', 'last_name', 'national_id', 'birth_date', 'type',
        'telegram_link', 'whatsapp_link', 'eitaa_link', 'address', 'province', 'city',
        'lat','log', 'is_active', 'documents', 'gallery','profile_image', 'about_me', 'guarantee',
         'website_link', "company_name","registration_number", "sms_notification",'blue_tick', 'fcm_token', 'notification', 'is_blue_tick_request'];
    
    
    protected $casts = [
	    'is_active' => 'boolean',
	    'blue_tick'=>'boolean',
	    'sms_notification' => 'boolean',
	    'notification' => 'boolean',
	    'is_blue_tick_request' => 'boolean',
    ];
    
    public function wallet()
    {
        return $this->hasOne(Wallet::class);
    }

    public function video()
    {
        return $this->hasOne(ExpertVerifications::class);
    }

    
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function services()
    {
        return $this->belongsToMany(Service::class, 'expert_services');
    }

    public function documents()
    {
        return $this->hasMany(ExpertDocuments::class,'expert_id',);
    }

    public function BlueTickdocuments()
    {
        return $this->hasMany(ExpertDocuments::class,'expert_id',)->where('type', 'blueTick');
    }

    public function gallery()
    {
        return $this->hasMany(ExpertGallery::class, 'expert_id');
    }

    public function comments()
    {
        return $this->hasMany(Review::class, 'expert_id')->where('is_active', true);
    }

    public function Allcomments()
    {
        return $this->hasMany(Review::class, 'expert_id');
    }

    public function bids()
    {
        return $this->hasMany(Bid::class, 'expert_id');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'expert_id')->where('status', 'completed');
    }


}

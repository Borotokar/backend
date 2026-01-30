<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceType extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'image',];

    public function category(){
	    return $this->hasMany(Servicecategory::class, 'service_type_id');
	}

}

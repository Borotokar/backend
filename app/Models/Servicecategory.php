<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Servicecategory extends Model
{
    use HasFactory;


    protected $fillable = ['name', 'image', 'service_type_id', 'slogan'];


    public function types(){
        return $this->belongsTo(ServiceType::class, 'service_type_id', 'id');
}

  public function services(){
  return $this->hasMany(Service::class, 'servicecategory_id');
}
}

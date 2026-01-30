<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
      'title',
      'image',
      'commission',
      'des',
      'servicecategory_id',
      'is_active'
  ];

    public function servicecategories(){
      return $this->hasMany(Servicecategory::class, 'id', 'servicecategory_id');
    }

    public function questions(){
      return $this->hasMany(Question::class);
    }

    public function proposalTypes()
    {
        return $this->belongsToMany(ProposalType::class, 'service_proposal_type');
    }
}

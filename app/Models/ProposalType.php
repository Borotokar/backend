<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Notifiable;

class ProposalType extends Model
{
    use HasFactory, Notifiable;
    protected $fillable = ['name'];
       
    

    public function services()
    {
        return $this->belongsToMany(Service::class, 'service_proposal_type');
    }
    
}

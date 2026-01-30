<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bid extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'expert_id',
        'proposed_price',
        'type',
        'description',
	    'proposal_type_id',
        'is_active'
    ];

    protected $casts = [
    'is_seen_by_user' => 'boolean',
    'is_active' => 'boolean',
    ];


    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function expert()
    {
        return $this->belongsTo(expert::class);
    }
    public function proposalType()
    {
        return $this->belongsTo(ProposalType::class, 'proposal_type_id');
    }
}

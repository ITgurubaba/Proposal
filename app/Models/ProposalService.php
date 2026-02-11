<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ProposalService extends Model
{
    protected $fillable = [
        'proposal_id',
        'service_id',
        'price',
        'data', 
        'sort_order', 
    ];

    protected $casts = [
        'data' => 'array',
    ];

    public function proposal()
    {
        return $this->belongsTo(Proposal::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}

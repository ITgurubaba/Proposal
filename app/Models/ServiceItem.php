<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServiceItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_id',
        'name',   // Sub service name e.g. VAT Registration
        'price',  // Price (nullable for bulk services)
    ];

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function contents()
    {
        return $this->hasMany(ServiceContent::class);
    }
}

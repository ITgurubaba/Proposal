<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Service extends Model
{
    protected $fillable = [
        'name',
        'base_price',
        'pricing_type', // individual / bulk
        'status', // active / inactive
    ];

    public function fields()
    {
        return $this->hasMany(ServiceField::class);
    }

    public function items()
    {
        return $this->hasMany(ServiceItem::class);
    }
}

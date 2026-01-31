<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ServiceField extends Model
{
    protected $fillable = [
        'service_id',
        'field_name',
        'field_label',
        'field_type', // text, number, select, date
        'options',    // json
        'is_required'
    ];

    protected $casts = [
        'options' => 'array',
    ];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}

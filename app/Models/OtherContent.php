<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OtherContent extends Model
{
    protected $fillable = [
        'title',
        'content',
        'status',
    ];
}

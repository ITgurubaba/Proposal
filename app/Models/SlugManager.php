<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class SlugManager extends Model
{
    use HasFactory;

    protected $fillable = [
        'prefix',
        'model',
        'model_id',
        'name',
        'slug'
    ];

    public function themePage():HasOne
    {
        return $this->hasOne(ThemePage::class,'id','model_id');
    }
}

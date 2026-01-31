<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ThemePageAttribute extends Model
{
    use HasFactory;

    protected $fillable = [
        'theme_page_id',
        'key',
        'value',
    ];

    public function themePage():HasOne
    {
        return $this->hasOne(ThemePage::class,'id','theme_page_id');
    }

}

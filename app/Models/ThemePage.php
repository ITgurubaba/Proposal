<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ThemePage extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'content',
        'authenticate',
        'status',
    ];

    public function attributes():HasMany
    {
        return $this->hasMany(ThemePageAttribute::class,'theme_page_id','id');
    }

    public function getSlug(): HasOne
    {
        return $this->hasOne(SlugManager::class,'model_id','id')->where('model',self::class);
    }

    public function metaData(): HasOne
    {
        return $this->hasOne(MetaData::class,'model_id','id')->where('model',self::class);
    }

    public function getSlugAttribute():?string
    {
        return $this->getSlug()->exists()?$this->getSlug->slug:"";
    }

}

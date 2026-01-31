<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ThemeSetting extends Model
{
    protected $fillable = ['key','value'];

    public static function findByKeys($keys = []):array
    {
        return self::whereIn('key',$keys)
            ->pluck('value','key')
            ->toArray();
    }

    public static function findByKey($key = null)
    {
        return self::where('key',$key)->first();
    }

}

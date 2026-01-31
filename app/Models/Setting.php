<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = ['key','value'];

    public static function getByKeys($keys = []):array
    {
        return self::whereIn('key',$keys)
            ->pluck('value','key')
            ->toArray();
    }

    public static function getByKey($key = null)
    {
        return self::where('key',$key)
            ->first();
    }

}

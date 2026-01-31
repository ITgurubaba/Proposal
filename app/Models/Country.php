<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{

    use HasFactory;

    protected $fillable = [
        "iso",
        "name",
        "nicename",
        "iso3",
        "numcode",
        "phonecode",
        "region_id",
        "timezone",
        "utcname",
        "utc",
        "abbreviations",
        "status",
    ];

    protected static function boot():void {
        parent::boot();

        static::deleting(static function($check) {

        });
    }


}

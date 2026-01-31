<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class Currency extends Model
{
    protected $table = "currencies";

    protected $fillable = [
        'name',
        'code',
        'symbol',
        'format',
        'exchange_rate',
        'active',
    ];

    public static function findByCode($code = "USD")
    {
        return self::where('code',$code)->first();
    }
    public static function updateExchangeRateByCode($code = "USD",$rate = 1): void
    {
        try
        {
            self::where('code',$code)->update([
                'exchange_rate'=>$rate
            ]);
        }
        catch (\Exception $exception){}
    }

    public static function createOrUpdate($data = []):self
    {
        if(Arr::has($data,'id'))
        {
            $check = self::find($data['id']);
        }
        else
        {
            $check = new self();
        }
        $check->fill(Arr::except($data,'id'));
        $check->save();
        return $check;
    }

}

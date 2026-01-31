<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VatTax extends Model
{
    protected $table = 'vat_taxes';

    const TYPES = ['percentage','amount'];

    const TYPE_PERCENTAGE = 'percentage';
    const TYPE_AMOUNT = 'amount';

    protected $fillable = [
        'type',
        'name',
        'description',
        'rate',
        'status',
    ];

    public function discountLabel($withCurrency = true):?string
    {
        if($this->type == self::TYPE_PERCENTAGE)
        {
            return $this->rate."%";
        }

        return $withCurrency?currency($this->rate ??0):$this->rate;
    }
}

<?php

namespace App\Helpers\Ecommerce;

use App\Models\DiscountCoupon;
use Illuminate\Support\Str;

class CouponHelper
{
    public static function getDiscountTypes():array
    {
        return [
            [
                'label'=>'Percentage',
                'value'=>DiscountCoupon::TYPE_PERCENTAGE,
            ],
            [
                'label'=>'Amount',
                'value'=>DiscountCoupon::TYPE_AMOUNT,
            ],
        ];
    }

    public static function generateCode($length = 8): string
    {
        do {
            $code = Str::random($length);
        }
        while(DiscountCoupon::where('code', $code)->count()>0);

        return $code;
    }
}

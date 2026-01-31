<?php

namespace App\Rules;

use App\Models\DiscountCoupon;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidateCouponRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $coupon = DiscountCoupon::findByCode($value);

        if($coupon)
        {
             if(!$coupon->isValid())
             {
                 $fail(':arrtribute is not valid');
             }
        }

    }
}

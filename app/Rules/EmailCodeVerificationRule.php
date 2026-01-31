<?php

namespace App\Rules;

use App\Models\EmailVerification;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class EmailCodeVerificationRule implements ValidationRule
{
    public string $email;

    public function __construct($email)
    {
        $this->email = $email;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {

        $check = EmailVerification::where([
            'email'=>$this->email,
            'code'=>$value,
            'status'=>0
        ])->count();

        if($check == 0)
        {
            $fail('The provided verification code is invalid');
        }

    }
}

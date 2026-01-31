<?php

namespace App\Rules;

use App\Models\User;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class PasswordMatch implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */

    public mixed $email = "";

    public function __construct($email = "")
    {
        $this->email = $email;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $user = User::where('email',$this->email)->first();

        if ($user)
        {
            return Hash::check($value,$user->password);
        }

        return  false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The password is wrong';
    }
}

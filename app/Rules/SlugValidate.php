<?php

namespace App\Rules;

use App\Helpers\Admin\SlugHelper;
use Illuminate\Contracts\Validation\Rule;

class SlugValidate implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */

    public mixed $data;

    public function __construct($data = [])
    {
        $this->data = $data;
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
        return SlugHelper::SlugAvailable($value,$this->data);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute not available.';
    }
}

<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class TransactionHash implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
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
        return 64 === strlen($value) && ctype_xdigit($value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute must be a 64-character case-sensitive hexadecimal string.';
    }
}

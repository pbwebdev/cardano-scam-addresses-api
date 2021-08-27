<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class CardanoAddress implements Rule
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
     * @param  mixed   $value
     *
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        if (0 !== strpos($value, 'addr1') && 0 !== strpos($value, 'stake1')) {
            return false;
        }

        return preg_match('/^[a-zA-Z0-9]{8,256}$/', $value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return 'The :attribute must be in Native SegWit (Bech32) format.';
    }
}

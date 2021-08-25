<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class WebsiteAddress implements Rule
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
        return preg_match('/^(?!:\/\/)(?=.{1,255}$)((.{1,63}\.){1,127}(?!\d*$)[a-z0-9-]+\.?)$/i', $value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return 'The :attribute must be a fully qualified domain name.';
    }
}

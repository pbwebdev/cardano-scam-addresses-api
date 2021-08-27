<?php

namespace App\Rules;

use App\Bech32;
use Illuminate\Contracts\Validation\Rule;

class Bech32Address implements Rule
{
    /**
     * @var Bech32
     */
    private $bech32;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->bech32 = new Bech32();
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
        return $this->isValid($value);
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

    /**
     * Check if an address is a valid Bech32
     *
     * @param  string  $address
     *
     * @return bool
     */
    public function isValid(string $address): bool
    {
        $decoded = $this->bech32->decode($address);

        return (bool)$decoded;
    }
}

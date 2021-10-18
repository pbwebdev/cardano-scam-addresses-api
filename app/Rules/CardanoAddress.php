<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class CardanoAddress implements Rule
{
    protected const HRPS = [
        'mainnet' => 'addr1',
        'testnet' => 'addr_test1',
    ];

    protected const STAKES = [
        'mainnet' => 'stake1',
        'testnet' => 'stake_test1',
    ];

    /**
     * @var string
     */
    private $network;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        $network = config('services.cardano.query_network');

        if (! array_key_exists($network, self::HRPS)) {
            $network = 'testnet';
        }

        $this->network = $network;
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
        return $this->isValid($value) || $this->isStake($value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return 'The :attribute must be from the ' . $this->getCurrentNetwork() . '.';
    }

    /**
     * Check if an address is from correct network.
     *
     * @param  string  $address
     *
     * @return bool
     */
    public function isValid(string $address): bool
    {
        $hrp = self::HRPS[$this->network];

        return false !== strpos($address, $hrp);
    }

    /**
     * Check if a stake address.
     *
     * @param  string  $address
     *
     * @return bool
     */
    public function isStake(string $address): bool
    {
        $hrp = self::STAKES[$this->network];

        return false !== strpos($address, $hrp);
    }


    /**
     * @return string
     */
    public function getCurrentNetwork(): string
    {
        return $this->network . ' network';
    }
}

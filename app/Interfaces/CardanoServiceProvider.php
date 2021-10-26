<?php

namespace App\Interfaces;

interface CardanoServiceProvider
{
    /**
     * Get the stake address that controls the key
     *
     * @param $key
     *
     * @return string
     */
    public function getStakeAddress($key): string;

    /**
     * Get the inputs and outputs of specific transaction
     *
     * @param $hash
     *
     * @return array
     */
    public function getTransactionDetails($hash): array;
}

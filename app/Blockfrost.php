<?php

namespace App;

use App\Interfaces\CardanoServiceProvider;
use App\Oauth2\BlockfrostClient;

class Blockfrost implements CardanoServiceProvider
{
    /**
     * @var BlockfrostClient
     */
    private $client;

    /**
     * Create a new Blockfrost instance
     */
    public function __construct(BlockfrostClient $client)
    {
        $this->client = $client;
    }

    /**
     * Get the stake address that controls the key
     *
     * @param $key
     *
     * @return string
     */
    public function getStakeAddress($key): string
    {
        $response = $this->client->request('addresses/' . $key);

        if ($response->isClientError()) {
            return '';
        }

        $data = $response->getData(true);

        return $data['stake_address'];
    }

    /**
     * Get the inputs and outputs of specific transaction
     *
     * @param $hash
     *
     * @return array
     */
    public function getTransactionDetails($hash): array
    {
        $response = $this->client->request('txs/' . $hash . '/utxos');

        if ($response->isClientError()) {
            return [];
        }

        return $response->getData(true);
    }
}

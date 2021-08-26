<?php

namespace App;

use App\Oauth2\BlockfrostClient;

class Blockfrost
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
}

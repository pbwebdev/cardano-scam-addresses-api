<?php

namespace App;

use App\Oauth2\TangoCryptoClient;

class TangoCrypto
{
    /**
     * @var TangoCryptoClient
     */
    private $client;

    /**
     * Create a new Blockfrost instance
     */
    public function __construct(TangoCryptoClient $client)
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

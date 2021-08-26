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
}

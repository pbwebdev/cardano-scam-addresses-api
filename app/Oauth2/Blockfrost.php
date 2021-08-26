<?php

namespace App\Oauth2;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;

class Blockfrost
{
    private $client;
    private $project_id;

    public const ENDPOINT = [
        'mainnet' => 'https://cardano-mainnet.blockfrost.io/api/v0/',
        'testnet' => 'https://cardano-testnet.blockfrost.io/api/v0/',
    ];

    /**
     * Create a new Blockfrost instance
     */
    public function __construct($project_id, $network = 'testnet')
    {
        $this->client = new Client([
            'base_uri' => self::ENDPOINT[$network],
        ]);
        $this->project_id = $project_id;
    }

    /**
     * Make a GET request to the API endpoint
     *
     * @param  string  $endpoint
     * @param  array   $params
     *
     * @return array
     */
    public function request(string $endpoint, array $params = []): array
    {
        try {
            $options = array_merge(
                [
                    'headers' => [
                        'project_id' => $this->project_id
                    ],
                    'query' => $params,
                ],
            );
            $response = $this->client->request('GET', $endpoint, $options);

            return [
                'code' => $response->getStatusCode(),
                'data' => json_decode($response->getBody(), true),
            ];
        } catch (RequestException $error) {
            $response = $error->getResponse();

            return json_decode($response->getBody(), true);
        } catch (GuzzleException $e) {
            return [
                'code'  => 500,
                'error' => $e->getMessage(),
            ];
        }
    }
}

<?php

namespace App\Oauth2;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\JsonResponse;
use JsonException;

class BlockfrostClient
{
    private $client;
    private $project_id;

    public const ENDPOINT = [
        'mainnet' => 'https://cardano-mainnet.blockfrost.io/api/v0/',
        'testnet' => 'https://cardano-testnet.blockfrost.io/api/v0/',
    ];

    /**
     * Create a new BlockfrostClient instance
     */
    public function __construct($project_id, $network = 'testnet')
    {
        if (! array_key_exists($network, self::ENDPOINT)) {
            $network = 'testnet';
        }

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
     * @return JsonResponse
     */
    public function request(string $endpoint, array $params = []): JsonResponse
    {
        try {
            $options = array_merge(
                [
                    'headers' => [
                        'project_id' => $this->project_id
                    ],
                    'query'   => $params,
                ],
            );
            $response = $this->client->request('GET', $endpoint, $options);

            $value = [
                'status_code' => $response->getStatusCode(),
                'data'        => json_decode($response->getBody(), true, 512, JSON_THROW_ON_ERROR),
            ];
        } catch (RequestException $error) {
            $response = $error->getResponse();

            try {
                $value = json_decode($response->getBody(), true, 512, JSON_THROW_ON_ERROR);
            } catch (JsonException $e) {
                $value = [
                    'status_code' => 500,
                    'error'       => $e->getMessage(),
                ];
            }
        } catch (GuzzleException $e) {
            $value = [
                'status_code' => 500,
                'error'       => $e->getMessage(),
            ];
        } catch (JsonException $e) {
            $value = [
                'status_code' => 500,
                'error'       => $e->getMessage(),
            ];
        } finally {
            return new JsonResponse($value['data'] ?? [], $value['status_code']);
        }
    }
}

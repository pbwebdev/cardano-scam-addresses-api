<?php

namespace App\Oauth2;

use Illuminate\Http\Client\RequestException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;

class BlockfrostClient
{
    private $http;

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

        $this->http = Http::baseUrl(self::ENDPOINT[$network])->withHeaders(compact('project_id'));
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
            $response = $this->http->get($endpoint, $params);

            $response->throw();

            $value = [
                'status_code' => $response->status(),
                'data'        => $response->json(),
            ];
        } catch (RequestException $error) {
            $value = [
                'status_code' => $error->getCode(),
                'error'       => $error->getMessage(),
            ];
        } finally {
            return new JsonResponse($value['data'] ?? [], $value['status_code']);
        }
    }
}

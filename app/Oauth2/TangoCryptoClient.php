<?php

namespace App\Oauth2;

use Illuminate\Http\Client\RequestException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;

class TangoCryptoClient
{
    private $http;

    public const ENDPOINT = [
        'mainnet' => 'https://cardano-mainnet.tangocrypto.com/',
        'testnet' => 'https://cardano-testnet.tangocrypto.com/',
    ];

    /**
     * Create a new TangoCryptoClient instance
     */
    public function __construct($account_id, $x_api_key, $network = 'testnet')
    {
        if (! array_key_exists($network, self::ENDPOINT)) {
            $network = 'testnet';
        }

        $this->http = Http::baseUrl(self::ENDPOINT[$network] . $account_id . '/v1')->withHeaders([
            'x-api-key' => $x_api_key,
        ]);
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

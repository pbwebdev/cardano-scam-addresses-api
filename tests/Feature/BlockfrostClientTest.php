<?php

namespace Tests\Feature;

use App\Oauth2\BlockfrostClient;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;
use Tests\Liberatable;
use Tests\TestCase;

class BlockfrostClientTest extends TestCase
{
    use Liberatable;

    /**
     * @var BlockfrostClient
     */
    private $blockfrostClient;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        Http::fake([
            '*' => Http::sequence()->pushStatus(200)->pushStatus(400)->pushStatus(500),
        ]);

        $this->blockfrostClient = new BlockfrostClient('key', 'asd');
    }

    public function test_network_defaults_to_testnet(): void
    {
        $property = $this->getPrivateProperty(BlockfrostClient::class, 'http');

        $this->assertNotNull($property);

        /**
         * @var $pendingRequest PendingRequest
         */
        $pendingRequest = $property->getValue($this->blockfrostClient);

        $property = $this->getPrivateProperty($pendingRequest, 'baseUrl');

        $this->assertNotNull($property);

        $baseUrl = $property->getValue($pendingRequest);

        $this->assertEquals($this->blockfrostClient::ENDPOINT['testnet'], $baseUrl);
    }

    public function test_response_is_in_json(): void
    {
        $response = $this->blockfrostClient->request('/');

        $this->assertInstanceOf(JsonResponse::class, $response);

        $response = $this->blockfrostClient->request('/');

        $this->assertInstanceOf(JsonResponse::class, $response);

        $response = $this->blockfrostClient->request('/');

        $this->assertInstanceOf(JsonResponse::class, $response);
    }
}

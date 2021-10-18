<?php

namespace Tests\Feature;

use App\Oauth2\TangoCryptoClient;
use App\TangoCrypto;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class TangoCryptoTest extends TestCase
{
    protected function getTangoCrypto(): TangoCrypto
    {
        $blockfrostClient = new TangoCryptoClient('id', 'key');

        return new TangoCrypto($blockfrostClient);
    }

    public function test_stake_address_is_passed(): void
    {
        Http::fake([
            '*' => Http::response([
                'address'       => 'addr1',
                'network'       => 'testnet',
                'stake_address' => 'stake1',
                'type'          => 'shelley',
                'ada'           => 0,
                'assets'        => [],
            ]),
        ]);

        $blockfrost = $this->getTangoCrypto();

        $stake = $blockfrost->getStakeAddress('addr1');

        $this->assertEquals('stake1', $stake);
    }

    public function test_stake_address_is_empty(): void
    {
        Http::fake([
            '*' => Http::response([
                'status_code' => 400,
                'error'       => 'Bad Request',
                'message'     => 'Backend did not understand your request.',
            ], 400),
        ]);

        $blockfrost = $this->getTangoCrypto();

        $stake = $blockfrost->getStakeAddress('addr1');

        $this->assertEmpty($stake);
    }
}

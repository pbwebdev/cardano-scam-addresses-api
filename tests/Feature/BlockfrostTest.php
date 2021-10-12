<?php

namespace Tests\Feature;

use App\Blockfrost;
use App\Oauth2\BlockfrostClient;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class BlockfrostTest extends TestCase
{
    protected function getBlockfrost(): Blockfrost
    {
        $blockfrostClient = new BlockfrostClient('key');

        return new Blockfrost($blockfrostClient);
    }

    public function test_stake_address_is_passed(): void
    {
        Http::fake([
            '*' => Http::response([
                'address'       => 'addr1',
                'amount'        => [],
                'stake_address' => 'stake1',
                'type'          => 'shelley',
            ]),
        ]);

        $blockfrost = $this->getBlockfrost();

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

        $blockfrost = $this->getBlockfrost();

        $stake = $blockfrost->getStakeAddress('addr1');

        $this->assertEmpty($stake);
    }
}

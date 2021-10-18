<?php

namespace Tests\Unit;

use App\Rules\CardanoAddress;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class CardanoAddressTest extends TestCase
{
    public function test_address_is_valid(): void
    {
        Config::set('services.cardano.query_network', 'mainnet');

        $cardanoAddress = new CardanoAddress();

        $this->assertTrue($cardanoAddress->isValid('addr1'));

        Config::set('services.cardano.query_network', 'testnet');

        $cardanoAddress = new CardanoAddress();

        $this->assertTrue($cardanoAddress->isValid('addr_test1'));

        Config::set('services.cardano.query_network', 'unknown');

        $cardanoAddress = new CardanoAddress();

        $this->assertTrue($cardanoAddress->isValid('addr_test1'));
    }

    public function test_address_is_stake(): void
    {
        Config::set('services.cardano.query_network', 'mainnet');

        $cardanoAddress = new CardanoAddress();

        $this->assertTrue($cardanoAddress->isStake('stake1'));

        Config::set('services.cardano.query_network', 'testnet');

        $cardanoAddress = new CardanoAddress();

        $this->assertTrue($cardanoAddress->isStake('stake_test1'));

        Config::set('services.cardano.query_network', 'unknown');

        $cardanoAddress = new CardanoAddress();

        $this->assertTrue($cardanoAddress->isStake('stake_test1'));
    }

    public function test_address_is_invalid(): void
    {
        $cardanoAddress = new CardanoAddress();

        $this->assertFalse($cardanoAddress->isValid('bc1qw508d6qejxtdg4y5r3zarvary0c5xw7kv8f3t4'));


        $this->assertFalse($cardanoAddress->isValid(''));


        $this->assertFalse($cardanoAddress->isStake('bc1qw508d6qejxtdg4y5r3zarvary0c5xw7kv8f3t4'));


        $this->assertFalse($cardanoAddress->isStake(''));
    }
}

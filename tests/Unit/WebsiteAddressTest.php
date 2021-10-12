<?php

namespace Tests\Unit;

use App\Rules\WebsiteAddress;
use PHPUnit\Framework\TestCase;

class WebsiteAddressTest extends TestCase
{
    /**
     * @var WebsiteAddress
     */
    private $websiteAddress;

    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->websiteAddress = new WebsiteAddress();
    }

    public function test_value_passes(): void
    {
        $valid = $this->websiteAddress->passes('address', 'a.b');

        $this->assertTrue($valid);

        $valid = $this->websiteAddress->passes('address', 'cd.ef');

        $this->assertTrue($valid);

        $valid = $this->websiteAddress->passes('address', '12.gh');

        $this->assertTrue($valid);
    }

    public function test_value_fails(): void
    {
        $valid = $this->websiteAddress->passes('address', '');

        $this->assertFalse($valid);

        $valid = $this->websiteAddress->passes('address', '.');

        $this->assertFalse($valid);

        $valid = $this->websiteAddress->passes('address', '1.1');

        $this->assertFalse($valid);
    }
}

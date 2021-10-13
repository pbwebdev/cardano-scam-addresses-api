<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Jetstream\Features;
use Tests\TestCase;

class CreateApiTokenTest extends TestCase
{
    use RefreshDatabase;

    public function test_api_tokens_can_be_created()
    {
        if (! Features::hasApiFeatures()) {
            return $this->markTestSkipped('API support is not enabled.');
        }

        if (Features::hasTeamFeatures()) {
            $this->actingAs($user = User::factory()->withPersonalTeam()->create());
        } else {
            $this->actingAs($user = User::factory()->create());
        }

        $response = $this->post('/user/api-tokens', [
            'name'        => 'Test Token',
            'permissions' => [
                'write_wallet',
                'write_website',
            ],
        ]);

        $this->assertCount(1, $user->fresh()->tokens);
        $this->assertEquals('Test Token', $user->fresh()->tokens->first()->name);
        $this->assertTrue($user->fresh()->tokens->first()->can('write_wallet'));
        $this->assertTrue($user->fresh()->tokens->first()->can('write_website'));
        $this->assertFalse($user->fresh()->tokens->first()->can('update'));
        $this->assertFalse($user->fresh()->tokens->first()->can('delete'));
    }
}

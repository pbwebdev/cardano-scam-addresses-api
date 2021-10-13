<?php

namespace Tests\Feature;

use App\Http\Resources\WebsiteCollection;
use App\Http\Resources\WebsiteResource;
use App\Models\User;
use App\Models\Website;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class WebsiteControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function authenticatedUser(bool $is_admin = false, bool $incorrect_permission = false): void
    {
        Sanctum::actingAs(
            User::factory(compact('is_admin'))->create(),
            $incorrect_permission ? [] : ['write_website']
        );
    }

    public function test_websites_are_listed_correctly(): void
    {
        $resource = Website::factory()->count(5)->create();
        $websites = new WebsiteCollection($resource);
        $response = $this->get(route('websites.index'));

        $response->assertOk();
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'address',
                ],
            ],
        ]);

        $response->assertJson($websites->response()->getData(true), true);
    }

    public function test_website_is_created_correctly(): void
    {
        $this->authenticatedUser();

        $payload = ['address' => 'created.com'];
        $response = $this->post(route('websites.store'), $payload);

        $response->assertCreated();
        $this->assertDatabaseHas('websites', $payload);
    }

    public function test_website_is_viewed_correctly(): void
    {
        $resource = Website::factory()->create();
        $website = new WebsiteResource($resource);
        $response = $this->get(route('websites.show', $resource->getAttributeValue('address')));

        $response->assertOk();
        $response->assertJsonStructure([
            'data' => [
                'id',
                'address',
            ],
        ]);
        $response->assertJson($website->response()->getData(true), true);
    }

    public function test_website_is_changed_correctly(): void
    {
        $this->authenticatedUser(true);

        $resource = Website::create(['address' => 'created.com']);
        $payload = ['address' => 'updated.com'];
        $response = $this->patch(route('websites.update', $resource->getAttributeValue('id')), $payload);

        $response->assertOk();
        $this->assertDatabaseHas('websites', $payload);
    }

    public function test_website_is_deleted_correctly(): void
    {
        $this->authenticatedUser(true);

        /**
         * @var Collection $resource
         */
        $resource = Website::factory()->count(5)->create();
        $website = $resource->first();
        $response = $this->delete(route('websites.destroy', $website->getAttributeValue('id')));

        $response->assertNoContent();
        $this->assertDatabaseMissing('websites', $website->toArray());
    }

    public function test_endpoints_need_correct_authorization(): void
    {
        $resource = Website::create(['address' => 'created.com']);
        $payload = ['address' => 'test.com'];

        $response = $this->post(route('websites.store'), $payload);

        $response->assertUnauthorized();

        $response = $this->patch(route('websites.update', $resource->getAttributeValue('id')), $payload);

        $response->assertUnauthorized();

        $response = $this->delete(route('websites.destroy', $resource->getAttributeValue('id')));

        $response->assertUnauthorized();
    }

    public function test_endpoints_need_correct_permission(): void
    {
        $resource = Website::create(['address' => 'created.com']);
        $payload = ['address' => 'test.com'];

        $this->authenticatedUser(false, true);

        $response = $this->post(route('websites.store'), $payload);

        $response->assertForbidden();

        $response = $this->patch(route('websites.update', $resource->getAttributeValue('id')), $payload);

        $response->assertForbidden();

        $response = $this->delete(route('websites.destroy', $resource->getAttributeValue('id')));

        $response->assertForbidden();
    }

    public function test_endpoints_need_administrator_role(): void
    {
        $resource = Website::create(['address' => 'created.com']);
        $payload = ['address' => 'test.com'];

        $this->authenticatedUser();

        $response = $this->patch(route('websites.update', $resource->getAttributeValue('id')), $payload);

        $response->assertForbidden();

        $response = $this->delete(route('websites.destroy', $resource->getAttributeValue('id')));

        $response->assertForbidden();
    }
}

<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Services\NewsletterService;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Mockery;

class NewsletterTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->newsletterService = Mockery::mock(NewsletterService::class);
        $this->app->instance(NewsletterService::class, $this->newsletterService);
    }

    public function testUpdateSubscriptionSuccess()
    {
        $user = User::factory()->create(['subscribed' => false]);

        $this->actingAs($user);

        $this->newsletterService
            ->shouldReceive('updateSubscription')
            ->once()
            ->with(Mockery::type('Illuminate\Http\Request'), $user->id)
            ->andReturn(true);

        $response = $this->put('/newsletter', ['subscribed' => true]);

        $response->assertStatus(302)
                 ->assertSessionHas('success', 'Subscription updated!');
    }

    public function testUpdateSubscriptionNoChange()
    {
        $user = User::factory()->create(['subscribed' => true]);

        $this->actingAs($user);

        $this->newsletterService
            ->shouldReceive('updateSubscription')
            ->once()
            ->with(Mockery::type('Illuminate\Http\Request'), $user->id)
            ->andReturn(true);

        $response = $this->put('/newsletter', ['subscribed' => true]);

        $response->assertStatus(302)
                 ->assertSessionHas('success', 'Subscription updated!');
    }

    public function testUpdateSubscriptionFailure()
    {
        $user = User::factory()->create(['subscribed' => false]);

        $this->actingAs($user);

        $this->newsletterService
            ->shouldReceive('updateSubscription')
            ->once()
            ->with(Mockery::type('Illuminate\Http\Request'), $user->id)
            ->andThrow(new \Exception('Subscription update failed'));

        $response = $this->put('/newsletter', ['subscribed' => true]);

        $response->assertSessionHasErrors(['error' => 'Failed to action. Please try again.']);

    }

    public function testUpdateSubscriptionValidationFailure()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $response = $this->put('/newsletter', ['subscribed' => 'not-a-boolean']);

        $response->assertSessionHasErrors('subscribed');
        
    }
}
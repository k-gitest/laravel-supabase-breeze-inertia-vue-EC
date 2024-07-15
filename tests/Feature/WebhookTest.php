<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Services\WebhookService;
use Mockery;
use Stripe\Event;

class WebhookTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->webhookService = $this->mock(WebhookService::class);
    }

    public function testHandleWebhookWithInvalidPayload()
    {
        $this->webhookService->shouldReceive('handleWebhook')
            ->once()
            ->andReturn(response()->json(['error' => 'Invalid payload'], 400));

        $response = $this->postJson('/stripe/webhook', [], [
            'Stripe-Signature' => 'test_signature'
        ]);

        $response->assertStatus(400)
                 ->assertJson(['error' => 'Invalid payload']);
    }

    public function testHandleWebhookWithValidPayload()
    {
        $event = Mockery::mock(Event::class);

        $this->webhookService->shouldReceive('handleWebhook')
            ->once()
            ->andReturn($event);

        $this->webhookService->shouldReceive('handleEvent')
            ->once()
            ->with($event);

        $response = $this->postJson('/stripe/webhook', [], [
            'Stripe-Signature' => 'test_signature'
        ]);

        $response->assertStatus(200)
                 ->assertJson(['status' => 'success']);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
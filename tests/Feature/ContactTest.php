<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Queue;
use App\Models\Contact;
use App\Events\ContactFormSubmitted;
use App\Services\ContactService;
use Mockery;

class ContactTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->contactService = Mockery::mock(ContactService::class);
        $this->app->instance(ContactService::class, $this->contactService);
    }

    public function testCreate()
    {
        $response = $this->get(route('contact.create'));

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page
            ->component('Contact/ContactForm')
        );
    }

    public function testStoreSuccess()
    {
        $this->contactService
            ->shouldReceive('createContact')
            ->once()
            ->andReturn(true);

        $response = $this->post(route('contact.store'), [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'message' => 'This is a test message',
        ]);

        $response->assertRedirect(route('contact.create'));
        $response->assertSessionHas('success', '送信しました');
    }

    public function testStoreValidationError()
    {
        $response = $this->post(route('contact.store'), [
            'name' => '',
            'email' => 'not-an-email',
            'message' => '',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['name', 'email', 'message']);
    }

    public function testStoreException()
    {
        $this->contactService
            ->shouldReceive('createContact')
            ->once()
            ->andThrow(new \Exception('Test Exception'));

        $response = $this
            ->from(route('contact.create'))
            ->post(route('contact.store'), [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'message' => 'This is a test message',
        ]);

        $response->assertSessionHasErrors(['error' => 'Failed to action. Please try again.']);

    }
    
}

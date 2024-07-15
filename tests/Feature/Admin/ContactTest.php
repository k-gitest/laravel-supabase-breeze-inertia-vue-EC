<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use App\Models\Contact;
use App\Models\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;

class ContactTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = Admin::factory()->create();
        $this->actingAs($this->admin, 'admin');
    }

    public function test_index_displays_contacts()
    {
        Contact::factory()->count(15)->create();

        $response = $this->get(route('admin.contact.index'));

        $response->assertStatus(200)
            ->assertInertia(fn (Assert $page) => $page
                ->component('Contact/Admin/AdminContactIndex')
                ->has('pagedata.data', 10) 
            );
    }

    public function test_show_displays_contact_details()
    {
        $contact = Contact::factory()->create();

        $response = $this->get(route('admin.contact.show', $contact->id));

        $response->assertStatus(200)
            ->assertInertia(fn (Assert $page) => $page
                ->component('Contact/Admin/AdminContactShow')
                ->has('data', fn (Assert $page) => $page
                    ->where('id', $contact->id)
                    ->where('name', $contact->name)
                    ->etc()
                )
            );
    }

    public function test_destroy_deletes_contact()
    {
        $contact = Contact::factory()->create();

        $response = $this->delete(route('admin.contact.destroy'), ['id' => (string) $contact->id]);

        $this->assertDatabaseMissing('contacts', [
            'id' => $contact->id,
        ]);

        $response->assertRedirect()
            ->assertSessionHas('success', '削除しました');
    }

    public function test_destroy_fails_if_contact_does_not_exist()
    {
        $response = $this->delete(route('admin.contact.destroy'), ['id' => 'non_existing_id']);

        $response->assertStatus(302)
            ->assertSessionMissing('success');
    }
}

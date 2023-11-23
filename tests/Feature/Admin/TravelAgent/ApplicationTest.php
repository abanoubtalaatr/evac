<?php

namespace Tests\Feature\Admin\TravelAgent;

use App\Http\Livewire\Admin\TravelAgent\Application;
use App\Models\VisaType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ApplicationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function application_component_loads()
    {
        Livewire::test(Application::class)
            ->assertSee(__('agent_applications'));
    }

    /** @test */
    public function application_component_renders_with_records()
    {
        // Assuming you have records in your test database
        // Add records or adjust the data according to your needs
        VisaType::factory()->create(['name' => 'Tourist']);

        Livewire::test(Application::class)
            ->assertSee(__('agent_applications'))
            ->assertSee('Tourist'); // Check if the rendered HTML contains the visa type

        // Add more assertions based on your component's expected behavior
    }

    /** @test */
    public function application_component_sends_email()
    {
        Livewire::test(Application::class)
            ->set('email', 'test@example.com')
            ->call('send')
            ->assertSee('send your now for this now');

        // Add more assertions based on your component's expected behavior
    }
}

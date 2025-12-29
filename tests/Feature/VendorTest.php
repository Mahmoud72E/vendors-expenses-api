<?php

namespace Tests\Feature\Vendor;

use App\Models\User;
use App\Models\Vendor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VendorTest extends TestCase
{
    use RefreshDatabase;

    private function admin()
    {
        return User::factory()->create(['role' => 'admin']);
    }

    /** @test */
    public function admin_can_create_vendor()
    {
        $response = $this->actingAs($this->admin(), 'sanctum')
                         ->postJson('/api/vendors', [
                             'name' => 'Amazon',
                         ]);

        $response->assertStatus(201);

        $this->assertDatabaseHas('vendors', [
            'name' => 'Amazon'
        ]);
    }

    /** @test */
    public function admin_can_list_vendors()
    {
        Vendor::factory()->count(13)->create();

        $response = $this->actingAs($this->admin(), 'sanctum')
                         ->getJson('/api/vendors');

        // Debug response for testing
        

        $response->assertStatus(200)
                 ->assertJsonCount(13, 'data');
    }
}

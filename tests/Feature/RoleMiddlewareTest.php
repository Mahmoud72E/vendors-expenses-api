<?php

namespace Tests\Feature\Middleware;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function non_admin_user_cannot_access_admin_routes()
    {
        $user = User::factory()->create([
            'role' => 'staff',
        ]);

        $response = $this->actingAs($user, 'sanctum')
                         ->getJson('/api/vendors');

        $response->assertStatus(403);
    }

    /** @test */
    public function admin_can_access_admin_routes()
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $response = $this->actingAs($admin, 'sanctum')
                         ->getJson('/api/vendors');

        $response->assertStatus(200);
    }
}

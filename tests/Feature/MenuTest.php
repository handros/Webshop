<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MenuTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_screen_can_be_rendered(): void
    {
        $response = $this->get('/');

        $response->assertOk();
    }

    public function test_auction_screen_can_be_rendered(): void
    {
        $response = $this->get('/auctions');

        $response->assertOk();
    }

    public function test_items_screen_can_be_rendered(): void
    {
        $response = $this->get('/items');

        $response->assertOk();
    }

    public function test_about_screen_can_be_rendered(): void
    {
        $response = $this->get('/about');

        $response->assertOk();
    }

    public function test_orders_screen_cant_be_rendered_for_guests(): void
    {
        $response = $this->get('/orders');

        $response->assertStatus(401);
    }

    public function test_orders_screen_can_be_rendered_for_users(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->get('/orders');

        $response->assertOk();
    }

    public function test_messages_screen_cant_be_rendered_for_guests(): void
    {
        $response = $this->get('/messages');

        $response->assertStatus(401);
    }

    public function test_messages_screen_can_be_rendered_for_users(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->get('/messages');

        $response->assertOk();
    }

    public function test_users_screen_cant_be_rendered_for_guests(): void
    {
        $response = $this->get('/users');

        $response->assertStatus(401);
    }

    public function test_users_screen_cant_be_rendered_for_not_admin_users(): void
    {
        $user = User::factory()->create([
            'is_admin' => false,
        ]);

        $response = $this
            ->actingAs($user)
            ->get('/users');

        $response->assertStatus(401);
    }

    public function test_users_screen_can_be_rendered_for_admin_users(): void
    {
        $user = User::factory()->create([
            'is_admin' => true,
        ]);

        $response = $this
            ->actingAs($user)
            ->get('/users');

        $response->assertOk();
    }
}

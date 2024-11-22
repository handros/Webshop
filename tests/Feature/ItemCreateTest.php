<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Item;
use App\Models\Label;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class ItemCreateTest extends TestCase
{
    use RefreshDatabase;

    public function test_item_creation_screen_cant_be_rendered_for_guests(): void
    {
        $response = $this->get('/items/create');

        $response->assertStatus(401);
    }

    public function test_item_creation_screen_cant_be_rendered_for_not_admin_user(): void
    {
        $user = User::factory()->create([
            'is_admin' => false,
        ]);

        $response = $this
            ->actingAs($user)
            ->get('/items/create');

        $response->assertStatus(403);
    }

    public function test_item_creation_screen_can_be_rendered_for_admin_user(): void
    {
        $user = User::factory()->create([
            'is_admin' => true,
        ]);

        $response = $this
            ->actingAs($user)
            ->get('/items/create');

        $response->assertOk();
    }

    public function test_item_cant_be_created_by_guests(): void
    {
        $image = UploadedFile::fake()->image('item_image.jpg', 600, 600);

        $response = $this->post('/items', [
            'name' => 'Test Item',
            'description' => 'This is a test item description.',
            'made_in' => 2022,
            'image' => $image,
        ]);

        $response->assertStatus(401);

        $this->assertDatabaseMissing('items', [
            'name' => 'Test Item',
            'description' => 'This is a test item description.',
            'made_in' => 2022,
        ]);
    }

    public function test_item_cant_be_created_by_not_admin_user(): void
    {
        $user = User::factory()->create([
            'is_admin' => false,
        ]);

        $image = UploadedFile::fake()->image('item_image.jpg', 600, 600);

        $response = $this
            ->actingAs($user)
            ->post('/items', [
                'name' => 'Test Item',
                'description' => 'This is a test item description.',
                'made_in' => 2024,
                'image' => $image,
        ]);

        $response->assertStatus(403);

        $this->assertDatabaseMissing('items', [
            'name' => 'User Item',
            'description' => 'This is a user item description.',
            'made_in' => 2024,
        ]);
    }

    public function test_valid_item_can_be_created_by_admin_user(): void
    {
        $user = User::factory()->create([
            'is_admin' => true,
        ]);

        $image = UploadedFile::fake()->image('item_image.jpg', 500, 500);

        $response = $this
            ->actingAs($user)
            ->post('/items', [
                'name' => 'Admin Item',
                'description' => 'This is an admin item description.',
                'made_in' => 2023,
                'image' => $image,
            ]);

        $response->assertValid(['name', 'description', 'made_in', 'image'])
                ->assertSessionHas('item_created')
                ->assertRedirect('/items');

        $this->assertDatabaseHas('items', [
            'name' => 'Admin Item',
            'description' => 'This is an admin item description.',
            'made_in' => 2023,
        ]);
    }

    public function test_invalid_item_cant_be_created_by_admin_user(): void
    {
        $user = User::factory()->create([
            'is_admin' => true,
        ]);

        $image = UploadedFile::fake()->image('item_image.jpg', 500, 500);

        $response = $this
            ->actingAs($user)
            ->post('/items', [
                'name' => 'Admin Item',
                'description' => 'This is an admin item description.',
                'made_in' => 'tree',
                'image' => 123,
            ]);

        $response->assertValid(['name', 'description'])
                ->assertInvalid(['made_in', 'image']);

        $this->assertDatabaseMissing('items', [
            'name' => 'Admin Item',
            'description' => 'This is an admin item description.',
            'made_in' => 'tree',
        ]);
    }

    public function test_valid_item_can_be_created_with_label_by_admin_user(): void
    {
        $user = User::factory()->create([
            'is_admin' => true,
        ]);

        $label1 = Label::factory()->create();
        $label2 = Label::factory()->create();

        $image = UploadedFile::fake()->image('item_image.jpg', 500, 500);

        $response = $this
            ->actingAs($user)
            ->post('/items', [
                'name' => 'Test Item',
                'description' => 'This is a test item description.',
                'made_in' => 2022,
                'labels' => [$label1->id, $label2->id],
                'image' => $image,
            ]);

        $response->assertValid(['name', 'description', 'made_in', 'image'])
            ->assertSessionHas('item_created')
            ->assertRedirect('/items');

        $this->assertDatabaseHas('item_label', [
            'item_id' => Item::latest()->first()->id,
            'label_id' => $label1->id,
        ]);

        $this->assertDatabaseHas('item_label', [
            'item_id' => Item::latest()->first()->id,
            'label_id' => $label2->id,
        ]);
    }
}

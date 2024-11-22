<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Item;
use App\Models\Label;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class ItemEditTest extends TestCase
{
    use RefreshDatabase;

    public function test_item_editor_screen_cant_be_rendered_for_guests(): void
    {
        $image= UploadedFile::fake()->image('item_image.jpg', 600, 600);
        $item = Item::factory()->create([
            'image' => $image,
        ]);
        $response = $this->get("/items/{$item->id}/edit");

        $response->assertStatus(401);
    }

    public function test_item_editor_screen_cant_be_rendered_for_not_admin_user(): void
    {
        $user = User::factory()->create([
            'is_admin' => false,
        ]);

        $image = UploadedFile::fake()->image('item_image.jpg', 600, 600);
        $item = Item::factory()->create([
            'image' => $image,
        ]);

        $response = $this
            ->actingAs($user)
            ->get("/items/{$item->id}/edit");

        $response->assertStatus(403);
    }

    public function test_item_editor_screen_can_be_rendered_for_admin_user(): void
    {
        $user = User::factory()->create([
            'is_admin' => true,
        ]);

        $image = UploadedFile::fake()->image('item_image.jpg', 600, 600);
        $item = Item::factory()->create([
            'image' => $image,
        ]);

        $response = $this
            ->actingAs($user)
            ->get("/items/{$item->id}/edit");

        $response->assertOk();
    }

    public function test_item_cant_be_edited_by_guests(): void
    {
        $image = UploadedFile::fake()->image('item_image.jpg', 600, 600);
        $item = Item::factory()->create([
            'name' => 'Item Name',
            'description' => 'Item description.',
            'made_in' => 1999,
            'image' => $image,
        ]);

        $response = $this
            ->patch("/items/{$item->id}", [
                'name' => 'Updated Item Name',
                'description' => 'Updated description.',
                'made_in' => 2023,
            ]);

        $response->assertStatus(401);

        $item->refresh();

        $this->assertEquals('Item Name', $item->name);
        $this->assertEquals('Item description.', $item->description);
        $this->assertEquals(1999, $item->made_in);

        $this->assertNotEquals('Updated Item Name', $item->name);
        $this->assertNotEquals('Updated description.', $item->description);
        $this->assertNotEquals(2023, $item->made_in);
    }

    public function test_item_cant_be_edited_by_not_admin_user(): void
    {
        $user = User::factory()->create([
            'is_admin' => false,
        ]);

        $image = UploadedFile::fake()->image('item_image.jpg', 600, 600);
        $item = Item::factory()->create([
            'name' => 'Item User Name',
            'description' => 'Item User description.',
            'made_in' => 1999,
            'image' => $image,
        ]);

        $response = $this
            ->actingAs($user)
            ->patch("/items/{$item->id}", [
                'name' => 'Updated Item Name',
                'description' => 'Updated description.',
                'made_in' => 2023,
            ]);

        $response->assertStatus(403);

        $item->refresh();

        $this->assertNotEquals('Updated Item Name', $item->name);
        $this->assertNotEquals('Updated description.', $item->description);
        $this->assertNotEquals(2023, $item->made_in);

        $this->assertEquals('Item User Name', $item->name);
        $this->assertEquals('Item User description.', $item->description);
        $this->assertEquals(1999, $item->made_in);
    }

    public function test_item_can_be_edited_by_admin_user(): void
    {
        $user = User::factory()->create([
            'is_admin' => true,
        ]);

        $image = UploadedFile::fake()->image('item_image.jpg', 500, 500);
        $item = Item::factory()->create([
            'name' => 'Item Admin Name',
            'description' => 'Item Admin description.',
            'made_in' => 2000,
            'image' => $image,
        ]);

        $response = $this
            ->actingAs($user)
            ->patch("/items/{$item->id}", [
                'name' => 'Updated Item Name',
                'description' => 'Updated description.',
                'made_in' => 2024,
            ]);

        $response->assertValid(['name', 'description', 'made_in'])
                ->assertSessionHas('item_updated')
                ->assertRedirect("/items/{$item->id}");

        $item->refresh();

        $this->assertEquals('Updated Item Name', $item->name);
        $this->assertEquals('Updated description.', $item->description);
        $this->assertEquals(2024, $item->made_in);

        $this->assertNotEquals('Item Admin Name', $item->name);
        $this->assertNotEquals('Item Admin description.', $item->description);
        $this->assertNotEquals(2000, $item->made_in);
    }

    public function test_item_cant_be_invalidly_edited_by_admin_user(): void
    {
        $user = User::factory()->create([
            'is_admin' => true,
        ]);

        $image = UploadedFile::fake()->image('item_image.jpg', 500, 500);
        $item = Item::factory()->create([
            'name' => 'Item Admin2 Name',
            'description' => 'Item Admin2 description.',
            'made_in' => 2001,
            'image' => $image,
        ]);

        $response = $this
            ->actingAs($user)
            ->patch("/items/{$item->id}", [
                'name' => true,
                'description' => 'Not Updated description.',
                'made_in' => $image,
            ]);

        $response->assertValid('description')
                ->assertInvalid(['name', 'made_in']);

        $item->refresh();

        $this->assertEquals('Item Admin2 Name', $item->name);
        $this->assertEquals('Item Admin2 description.', $item->description);
        $this->assertEquals(2001, $item->made_in);
    }

    public function test_item_with_label_can_be_edited_by_admin_user(): void
    {
        $user = User::factory()->create([
            'is_admin' => true,
        ]);
        $item = Item::factory()->create();
        $label1 = Label::factory()->create();
        $label2 = Label::factory()->create();

        $item->labels()->attach($label1->id);

        $response = $this
            ->actingAs($user)
                ->patch("/items/{$item->id}", [
                    'name' => 'Updated Item Name',
                    'description' => 'Updated description.',
                    'made_in' => 2023,
                    'on_auction' => false,
                    'labels' => [$label2->id],  // Attach the second label
                ]);

        $response->assertSessionHasNoErrors()
                ->assertRedirect("/items/{$item->id}");

        $item->refresh();

        $this->assertTrue($item->labels->contains($label2));
        $this->assertFalse($item->labels->contains($label1));
    }

}

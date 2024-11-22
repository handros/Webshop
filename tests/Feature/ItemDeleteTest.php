<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Item;
use App\Models\Label;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ItemDeleteTest extends TestCase
{
    use RefreshDatabase;

    public function test_item_cant_be_deleted_by_guest(): void
    {
        $item = Item::factory()->create();

        $response = $this
            ->delete("/items/{$item->id}");

        $response->assertStatus(401);

        $this->assertDatabaseHas('items', [
            'id' => $item->id,
        ]);
    }

    public function test_item_cant_be_deleted_by_not_admin_user(): void
    {
        $user = User::factory()->create([
            'is_admin' => false,
        ]);
        $item = Item::factory()->create();

        $response = $this
            ->actingAs($user)
            ->delete("/items/{$item->id}");

        $response->assertStatus(403);

        $this->assertDatabaseHas('items', [
            'id' => $item->id,
        ]);
    }

    public function test_item_can_be_deleted_by_admin_user(): void
    {
        $user = User::factory()->create([
            'is_admin' => true,
        ]);
        $item = Item::factory()->create();

        $response = $this
            ->actingAs($user)
            ->delete("/items/{$item->id}");

        $response->assertSessionHas('item_deleted')
                ->assertRedirect('/items');

        $this->assertDatabaseMissing('items', [
            'id' => $item->id,
        ]);
    }

    public function test_item_can_be_deleted_with_labels_by_admin_user(): void
    {
        $user = User::factory()->create([
            'is_admin' => true,
        ]);

        $item = Item::factory()->create();
        $item_id = $item->id;
        $label = Label::factory()->create();

        $item->labels()->attach($label->id);

        $response = $this
            ->actingAs($user)
            ->delete("/items/{$item->id}");

        $response->assertSessionHasNoErrors()
                ->assertRedirect('/items');

        $this->assertDatabaseMissing('items', [
            'id' => $item->id,
        ]);

        $this->assertDatabaseHas('labels', [
            'id' => $label->id,
        ]);

        $this->assertDatabaseMissing('item_label', [
            'item_id' => $item_id,
            'label_id' => $label->id,
        ]);
    }
}

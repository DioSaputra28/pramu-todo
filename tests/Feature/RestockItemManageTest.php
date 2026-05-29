<?php

use App\Models\Product;
use App\Models\RestockItem;
use App\Models\RestockList;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function makeRestockItem(int $quantity = 1): RestockItem
{
    $list = RestockList::query()->create(['status' => 'open']);
    $product = Product::factory()->create();

    return RestockItem::query()->create([
        'restock_list_id' => $list->id,
        'product_id' => $product->id,
        'quantity' => $quantity,
        'status' => 'pending',
        'scanned_at' => now(),
    ]);
}

it('increments the quantity of an item', function () {
    $item = makeRestockItem(2);

    $this->patch(route('restock-items.update', $item), ['action' => 'increment'])
        ->assertRedirect(route('todo'));

    expect($item->fresh()->quantity)->toBe(3);
});

it('decrements the quantity of an item', function () {
    $item = makeRestockItem(2);

    $this->patch(route('restock-items.update', $item), ['action' => 'decrement']);

    expect($item->fresh()->quantity)->toBe(1);
});

it('does not decrement quantity below one', function () {
    $item = makeRestockItem(1);

    $this->patch(route('restock-items.update', $item), ['action' => 'decrement']);

    expect($item->fresh()->quantity)->toBe(1);
});

it('rejects an invalid quantity action', function () {
    $item = makeRestockItem(1);

    $this->patch(route('restock-items.update', $item), ['action' => 'reset'])
        ->assertSessionHasErrors('action');
});

it('deletes an item from the todo list', function () {
    $item = makeRestockItem(1);

    $this->delete(route('restock-items.destroy', $item))
        ->assertRedirect(route('todo'));

    $this->assertDatabaseMissing('restock_items', ['id' => $item->id]);
});

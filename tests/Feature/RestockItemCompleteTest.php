<?php

use App\Models\Product;
use App\Models\RestockItem;
use App\Models\RestockList;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('marks a restock item as done and redirects to the todo page', function () {
    $list = RestockList::query()->create(['status' => 'open']);
    $product = Product::factory()->create();
    $item = RestockItem::query()->create([
        'restock_list_id' => $list->id,
        'product_id' => $product->id,
        'quantity' => 2,
        'status' => 'pending',
        'scanned_at' => now(),
    ]);

    $response = $this->patch(route('restock-items.complete', $item));

    $response->assertRedirect(route('todo'));
    $response->assertSessionHas('status');

    $this->assertDatabaseHas('restock_items', [
        'id' => $item->id,
        'status' => 'done',
    ]);
});

it('hides completed items from the todo list', function () {
    $list = RestockList::query()->create(['status' => 'open']);
    $product = Product::factory()->create(['name' => 'Teh Kotak']);
    $item = RestockItem::query()->create([
        'restock_list_id' => $list->id,
        'product_id' => $product->id,
        'quantity' => 1,
        'status' => 'pending',
        'scanned_at' => now(),
    ]);

    $this->patch(route('restock-items.complete', $item));

    $this->get(route('todo'))->assertDontSee('Teh Kotak');
});

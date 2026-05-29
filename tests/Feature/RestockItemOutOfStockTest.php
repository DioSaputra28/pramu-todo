<?php

use App\Models\Product;
use App\Models\RestockItem;
use App\Models\RestockList;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function makeTodoItem(string $status = 'pending', string $name = 'Teh Kotak'): RestockItem
{
    $list = RestockList::query()->create(['status' => 'open']);
    $product = Product::factory()->create(['name' => $name]);

    return RestockItem::query()->create([
        'restock_list_id' => $list->id,
        'product_id' => $product->id,
        'quantity' => 2,
        'status' => $status,
        'scanned_at' => now(),
    ]);
}

it('marks an item as out of stock', function () {
    $item = makeTodoItem();

    $response = $this->patch(route('restock-items.out-of-stock', $item));

    $response->assertRedirect(route('todo'));
    $response->assertSessionHas('status');

    $this->assertDatabaseHas('restock_items', [
        'id' => $item->id,
        'status' => 'out_of_stock',
    ]);
});

it('restores an out of stock item back to pending', function () {
    $item = makeTodoItem('out_of_stock');

    $this->patch(route('restock-items.restore', $item))
        ->assertRedirect(route('todo'));

    $this->assertDatabaseHas('restock_items', [
        'id' => $item->id,
        'status' => 'pending',
    ]);
});

it('separates pending and out of stock items on the todo page', function () {
    $list = RestockList::query()->create(['status' => 'open']);
    $pending = Product::factory()->create(['name' => 'Barang Pending']);
    $empty = Product::factory()->create(['name' => 'Barang Kosong']);

    RestockItem::query()->create([
        'restock_list_id' => $list->id,
        'product_id' => $pending->id,
        'quantity' => 1,
        'status' => 'pending',
        'scanned_at' => now(),
    ]);
    RestockItem::query()->create([
        'restock_list_id' => $list->id,
        'product_id' => $empty->id,
        'quantity' => 1,
        'status' => 'out_of_stock',
        'scanned_at' => now(),
    ]);

    $this->get(route('todo'))
        ->assertSee('Barang Pending')
        ->assertSee('Barang Kosong')
        ->assertSee('Stok Gudang Habis');
});

it('excludes out of stock items from the pending count', function () {
    $list = RestockList::query()->create(['status' => 'open']);
    $product = Product::factory()->create();
    RestockItem::query()->create([
        'restock_list_id' => $list->id,
        'product_id' => $product->id,
        'quantity' => 1,
        'status' => 'out_of_stock',
        'scanned_at' => now(),
    ]);

    $response = $this->get(route('todo'));

    $response->assertViewHas('itemCount', 0);
});

<?php

use App\Models\Product;
use App\Models\RestockItem;
use App\Models\RestockList;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function makeOpenListWithItem(): RestockList
{
    $list = RestockList::query()->create(['status' => 'open']);
    $product = Product::factory()->create();
    RestockItem::query()->create([
        'restock_list_id' => $list->id,
        'product_id' => $product->id,
        'quantity' => 2,
        'status' => 'pending',
        'scanned_at' => now(),
    ]);

    return $list;
}

it('completes the open session and moves it to history', function () {
    $list = makeOpenListWithItem();

    $response = $this->post(route('restock-lists.complete'));

    $response->assertRedirect(route('history.index'));
    $response->assertSessionHas('status');

    $this->assertDatabaseHas('restock_lists', [
        'id' => $list->id,
        'status' => 'completed',
    ]);
});

it('does not complete an empty session', function () {
    $list = RestockList::query()->create(['status' => 'open']);

    $this->post(route('restock-lists.complete'))
        ->assertRedirect(route('todo'));

    $this->assertDatabaseHas('restock_lists', [
        'id' => $list->id,
        'status' => 'open',
    ]);
});

it('lists only completed sessions on the history page', function () {
    $completed = makeOpenListWithItem();
    $completed->update(['status' => 'completed']);

    $open = makeOpenListWithItem();

    $this->get(route('history.index'))
        ->assertSee("Sesi #{$completed->id}")
        ->assertDontSee("Sesi #{$open->id}");
});

it('shows the items of a completed session', function () {
    $list = RestockList::query()->create(['status' => 'completed']);
    $product = Product::factory()->create(['name' => 'Teh Kotak']);
    RestockItem::query()->create([
        'restock_list_id' => $list->id,
        'product_id' => $product->id,
        'quantity' => 3,
        'status' => 'done',
        'scanned_at' => now(),
    ]);

    $this->get(route('history.show', $list))
        ->assertSee('Teh Kotak')
        ->assertSee('x3');
});

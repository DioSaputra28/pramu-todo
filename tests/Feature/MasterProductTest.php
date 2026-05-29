<?php

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('filters products by name or barcode using the search query', function () {
    Product::factory()->create(['name' => 'Teh Kotak', 'barcode' => '8990000000001']);
    Product::factory()->create(['name' => 'Kopi Sachet', 'barcode' => '8990000000002']);

    $this->get(route('master.index', ['q' => 'Teh']))
        ->assertSee('Teh Kotak')
        ->assertDontSee('Kopi Sachet');

    $this->get(route('master.index', ['q' => '8990000000002']))
        ->assertSee('Kopi Sachet')
        ->assertDontSee('Teh Kotak');
});

it('updates a product', function () {
    $product = Product::factory()->create(['name' => 'Nama Lama', 'barcode' => '8990000000001']);

    $response = $this->patch(route('master.update', $product), [
        'barcode' => '8990000000009',
        'name' => 'Nama Baru',
    ]);

    $response->assertRedirect(route('master.index'));
    $response->assertSessionHas('status');

    $this->assertDatabaseHas('products', [
        'id' => $product->id,
        'barcode' => '8990000000009',
        'name' => 'Nama Baru',
    ]);
});

it('allows keeping the same barcode when updating a product', function () {
    $product = Product::factory()->create(['barcode' => '8990000000001', 'name' => 'Teh']);

    $response = $this->patch(route('master.update', $product), [
        'barcode' => '8990000000001',
        'name' => 'Teh Manis',
    ]);

    $response->assertSessionHasNoErrors();
    $this->assertDatabaseHas('products', [
        'id' => $product->id,
        'name' => 'Teh Manis',
    ]);
});

it('rejects updating to a barcode used by another product', function () {
    Product::factory()->create(['barcode' => '8990000000001']);
    $product = Product::factory()->create(['barcode' => '8990000000002']);

    $response = $this->patch(route('master.update', $product), [
        'barcode' => '8990000000001',
        'name' => 'Apa Saja',
    ]);

    $response->assertSessionHasErrors('barcode');
});

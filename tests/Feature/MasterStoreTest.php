<?php

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('stores a new product and redirects to the master index', function () {
    $response = $this->post(route('master.store'), [
        'barcode' => '8991234567890',
        'name' => 'Teh Kotak',
    ]);

    $response->assertRedirect(route('master.index'));
    $response->assertSessionHas('status');

    $this->assertDatabaseHas('products', [
        'barcode' => '8991234567890',
        'name' => 'Teh Kotak',
    ]);
});

it('trims whitespace from barcode and name before saving', function () {
    $this->post(route('master.store'), [
        'barcode' => '  8990000000001  ',
        'name' => '  Kopi Sachet  ',
    ]);

    $this->assertDatabaseHas('products', [
        'barcode' => '8990000000001',
        'name' => 'Kopi Sachet',
    ]);
});

it('requires barcode and name', function () {
    $response = $this->post(route('master.store'), [
        'barcode' => '',
        'name' => '',
    ]);

    $response->assertSessionHasErrors(['barcode', 'name']);
    expect(Product::count())->toBe(0);
});

it('rejects a duplicate barcode', function () {
    Product::factory()->create(['barcode' => '8991234567890']);

    $response = $this->post(route('master.store'), [
        'barcode' => '8991234567890',
        'name' => 'Produk Lain',
    ]);

    $response->assertSessionHasErrors('barcode');
    expect(Product::count())->toBe(1);
});

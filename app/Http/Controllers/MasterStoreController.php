<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;

class MasterStoreController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(StoreProductRequest $request): RedirectResponse
    {
        $product = Product::query()->create([
            'barcode' => $request->string('barcode')->trim()->toString(),
            'name' => $request->string('name')->trim()->toString(),
        ]);

        return redirect()
            ->route('master.index')
            ->with('status', "Produk {$product->name} berhasil ditambahkan.");
    }
}

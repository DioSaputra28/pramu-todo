<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;

class MasterUpdateController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(UpdateProductRequest $request, Product $product): RedirectResponse
    {
        $product->update([
            'barcode' => $request->string('barcode')->trim()->toString(),
            'name' => $request->string('name')->trim()->toString(),
        ]);

        return redirect()
            ->route('master.index')
            ->with('status', "Produk {$product->name} berhasil diperbarui.");
    }
}

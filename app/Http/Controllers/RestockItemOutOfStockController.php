<?php

namespace App\Http\Controllers;

use App\Models\RestockItem;
use Illuminate\Http\RedirectResponse;

class RestockItemOutOfStockController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(RestockItem $restockItem): RedirectResponse
    {
        $restockItem->update([
            'status' => 'out_of_stock',
        ]);

        return redirect()
            ->route('todo')
            ->with('status', 'Barang ditandai stok gudang habis.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\RestockItem;
use Illuminate\Http\RedirectResponse;

class RestockItemRestoreController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(RestockItem $restockItem): RedirectResponse
    {
        $restockItem->update([
            'status' => 'pending',
        ]);

        return redirect()
            ->route('todo')
            ->with('status', 'Barang dikembalikan ke daftar ambil.');
    }
}

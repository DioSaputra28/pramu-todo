<?php

namespace App\Http\Controllers;

use App\Models\RestockItem;
use Illuminate\Http\RedirectResponse;

class RestockItemDestroyController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(RestockItem $restockItem): RedirectResponse
    {
        $restockItem->delete();

        return redirect()
            ->route('todo')
            ->with('status', 'Barang dihapus dari to-do.');
    }
}

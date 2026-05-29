<?php

namespace App\Http\Controllers;

use App\Models\RestockItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class RestockItemUpdateController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, RestockItem $restockItem): RedirectResponse
    {
        $validated = $request->validate([
            'action' => ['required', 'in:increment,decrement'],
        ]);

        if ($validated['action'] === 'increment') {
            $restockItem->increment('quantity');
        } elseif ($restockItem->quantity > 1) {
            $restockItem->decrement('quantity');
        }

        return redirect()
            ->route('todo')
            ->with('status', 'Jumlah barang diperbarui.');
    }
}

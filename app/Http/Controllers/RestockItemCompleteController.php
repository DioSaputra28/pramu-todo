<?php

namespace App\Http\Controllers;

use App\Models\RestockItem;
use Illuminate\Http\RedirectResponse;

class RestockItemCompleteController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(RestockItem $restockItem): RedirectResponse
    {
        $restockItem->update([
            'status' => 'done',
        ]);

        return redirect()
            ->route('todo')
            ->with('status', 'Item ditandai selesai diambil.');
    }
}

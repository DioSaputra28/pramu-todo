<?php

namespace App\Http\Controllers;

use App\Models\RestockList;
use Illuminate\View\View;

class HistoryIndexController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): View
    {
        $restockLists = RestockList::query()
            ->where('status', 'completed')
            ->withCount('items')
            ->withSum('items', 'quantity')
            ->latest('updated_at')
            ->get();

        return view('web.history', [
            'restockLists' => $restockLists,
        ]);
    }
}

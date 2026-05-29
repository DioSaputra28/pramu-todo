<?php

namespace App\Http\Controllers;

use App\Models\RestockList;
use Illuminate\View\View;

class HistoryShowController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(RestockList $restockList): View
    {
        $restockList->load(['items' => function ($query): void {
            $query->with('product:id,name,barcode')
                ->orderByDesc('scanned_at')
                ->orderByDesc('id');
        }]);

        return view('web.history-show', [
            'restockList' => $restockList,
        ]);
    }
}

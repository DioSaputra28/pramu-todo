<?php

namespace App\Http\Controllers;

use App\Models\RestockItem;
use App\Models\RestockList;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ScanController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): View
    {
        $restockList = RestockList::query()
            ->select(['id', 'status', 'created_at'])
            ->where('status', 'open')
            ->latest()
            ->first();

        $itemCount = 0;
        $latestItem = null;

        if ($restockList) {
            $itemCount = RestockItem::query()
                ->where('restock_list_id', $restockList->id)
                ->count();

            $latestItem = RestockItem::query()
                ->with(['product:id,name,barcode'])
                ->where('restock_list_id', $restockList->id)
                ->orderByDesc('scanned_at')
                ->orderByDesc('id')
                ->first();
        }

        return view('web.scan', [
            'itemCount' => $itemCount,
            'latestItem' => $latestItem,
        ]);
    }
}

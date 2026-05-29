<?php

namespace App\Http\Controllers;

use App\Models\RestockItem;
use App\Models\RestockList;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TodoController extends Controller
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

        $items = collect();

        if ($restockList) {
            $items = RestockItem::query()
                ->with(['product:id,name,barcode'])
                ->where('restock_list_id', $restockList->id)
                ->orderByDesc('scanned_at')
                ->orderByDesc('id')
                ->get();
        }

        return view('web.todo', [
            'items' => $items,
            'itemCount' => $items->count(),
        ]);
    }
}

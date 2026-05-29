<?php

namespace App\Http\Controllers;

use App\Models\RestockItem;
use App\Models\RestockList;
use Illuminate\Database\Eloquent\Collection;
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

        /** @var Collection<int, RestockItem> $items */
        $items = new Collection;

        if ($restockList) {
            $items = RestockItem::query()
                ->with(['product:id,name,barcode'])
                ->where('restock_list_id', $restockList->id)
                ->whereIn('status', ['pending', 'out_of_stock'])
                ->orderByDesc('scanned_at')
                ->orderByDesc('id')
                ->get();
        }

        $pendingItems = $items->where('status', 'pending')->values();
        $outOfStockItems = $items->where('status', 'out_of_stock')->values();

        return view('web.todo', [
            'pendingItems' => $pendingItems,
            'outOfStockItems' => $outOfStockItems,
            'itemCount' => $pendingItems->count(),
        ]);
    }
}

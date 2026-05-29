<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRestockItemRequest;
use App\Models\Product;
use App\Models\RestockItem;
use App\Models\RestockList;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class RestockItemStoreController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(StoreRestockItemRequest $request): JsonResponse
    {
        $barcode = $request->string('barcode')->trim()->toString();

        $product = Product::query()
            ->select(['id', 'name', 'barcode'])
            ->where('barcode', $barcode)
            ->first();

        if (! $product) {
            return response()->json([
                'message' => 'Barcode belum terdaftar.',
                'barcode' => $barcode,
                'addUrl' => route('master.create', ['barcode' => $barcode]),
            ], 404);
        }

        $restockList = RestockList::query()
            ->select(['id', 'status'])
            ->where('status', 'open')
            ->latest()
            ->first();

        if (! $restockList) {
            $restockList = RestockList::query()->create([
                'status' => 'open',
            ]);
        }

        $restockItem = DB::transaction(function () use ($restockList, $product): RestockItem {
            $existingItem = RestockItem::query()
                ->where('restock_list_id', $restockList->id)
                ->where('product_id', $product->id)
                ->lockForUpdate()
                ->first();

            if ($existingItem) {
                $existingItem->increment('quantity', 1, [
                    'status' => 'pending',
                    'scanned_at' => now(),
                ]);

                return $existingItem->fresh();
            }

            return RestockItem::query()->create([
                'restock_list_id' => $restockList->id,
                'product_id' => $product->id,
                'quantity' => 1,
                'status' => 'pending',
                'scanned_at' => now(),
            ]);
        });

        $itemCount = RestockItem::query()
            ->where('restock_list_id', $restockList->id)
            ->count();

        return response()->json([
            'message' => 'Item ditambahkan.',
            'itemCount' => $itemCount,
            'item' => [
                'id' => $restockItem->id,
                'name' => $product->name,
                'barcode' => $product->barcode,
                'quantity' => $restockItem->quantity,
            ],
        ]);
    }
}

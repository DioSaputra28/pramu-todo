<?php

namespace App\Http\Controllers;

use App\Models\RestockList;
use Illuminate\Http\RedirectResponse;

class RestockListCompleteController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): RedirectResponse
    {
        $restockList = RestockList::query()
            ->where('status', 'open')
            ->latest()
            ->first();

        if (! $restockList) {
            return redirect()
                ->route('todo')
                ->with('status', 'Tidak ada sesi aktif untuk diselesaikan.');
        }

        if ($restockList->items()->count() === 0) {
            return redirect()
                ->route('todo')
                ->with('status', 'Sesi kosong, belum ada barang yang discan.');
        }

        $restockList->update([
            'status' => 'completed',
        ]);

        return redirect()
            ->route('history.index')
            ->with('status', 'Sesi restock disimpan ke riwayat.');
    }
}

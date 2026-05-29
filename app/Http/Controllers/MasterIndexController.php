<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MasterIndexController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): View
    {
        $search = $request->string('q')->trim();

        $products = Product::query()
            ->select(['id', 'name', 'barcode'])
            ->when($search->isNotEmpty(), function (Builder $query) use ($search): void {
                $like = '%'.$search->toString().'%';

                $query->where(function (Builder $query) use ($like): void {
                    $query->where('name', 'like', $like)
                        ->orWhere('barcode', 'like', $like);
                });
            })
            ->orderBy('name')
            ->get();

        return view('web.master', [
            'products' => $products,
            'query' => $search->toString(),
        ]);
    }
}

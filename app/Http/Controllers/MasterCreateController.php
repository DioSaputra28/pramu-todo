<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MasterCreateController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): View
    {
        $barcode = $request->string('barcode')->trim()->toString();
        $existingProduct = null;

        if ($barcode !== '') {
            $existingProduct = Product::query()
                ->select(['id', 'name', 'barcode'])
                ->where('barcode', $barcode)
                ->first();
        }

        return view('web.master-create', [
            'barcode' => $barcode,
            'existingProduct' => $existingProduct,
        ]);
    }
}

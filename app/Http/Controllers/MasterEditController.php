<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\View\View;

class MasterEditController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Product $product): View
    {
        return view('web.master-edit', [
            'product' => $product,
        ]);
    }
}

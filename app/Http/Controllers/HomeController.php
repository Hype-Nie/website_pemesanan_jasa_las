<?php

namespace App\Http\Controllers;

use App\Models\CatalogProduct;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Show the landing page with active catalog products.
     */
    public function index()
    {
        $products = CatalogProduct::active()
            ->latest()
            ->take(6)
            ->get();

        return view('home', compact('products'));
    }
}

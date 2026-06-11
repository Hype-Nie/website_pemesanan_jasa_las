<?php

namespace App\Http\Controllers;

use App\Models\CatalogProduct;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    /**
     * List active products with optional category filter.
     */
    public function index(Request $request)
    {
        $query = CatalogProduct::active();

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        $products = $query->latest()->paginate(12);

        $categories = \App\Models\Category::all();

        return view('catalog.index', compact('products', 'categories'));
    }

    /**
     * Show a single product detail.
     */
    public function show(string $id)
    {
        $product = CatalogProduct::active()->findOrFail($id);

        return view('catalog.show', compact('product'));
    }
}

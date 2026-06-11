<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CatalogProduct;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    /**
     * List all products (active + inactive).
     */
    public function index()
    {
        $products = CatalogProduct::latest()->paginate(15);

        return view('admin.catalog.index', compact('products'));
    }

    /**
     * Show create product form.
     */
    public function create()
    {
        $categories = \App\Models\Category::all();

        return view('admin.catalog.create', compact('categories'));
    }

    /**
     * Validate and store a new catalog product.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'material' => 'required|string|max:255',
            'price_estimate' => 'required|numeric|min:0',
            'image' => 'nullable|file|mimes:jpg,jpeg,png,webp|max:3072',
            'is_active' => 'nullable|boolean',
        ]);

        $data = [
            'name' => strip_tags($validated['name']),
            'description' => strip_tags($validated['description']),
            'category_id' => $validated['category_id'],
            'material' => strip_tags($validated['material']),
            'price_estimate' => $validated['price_estimate'],
            'is_active' => $validated['is_active'] ?? true,
        ];

        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')
                ->store('catalog-images', 'public');
        }

        CatalogProduct::create($data);

        return redirect()
            ->route('admin.catalog.index')
            ->with('success', 'Produk katalog berhasil ditambahkan.');
    }

    /**
     * Show edit product form.
     */
    public function edit(string $id)
    {
        $product = CatalogProduct::findOrFail($id);
        $categories = \App\Models\Category::all();

        return view('admin.catalog.edit', compact('product', 'categories'));
    }

    /**
     * Update an existing catalog product.
     */
    public function update(Request $request, string $id)
    {
        $product = CatalogProduct::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'material' => 'required|string|max:255',
            'price_estimate' => 'required|numeric|min:0',
            'image' => 'nullable|file|mimes:jpg,jpeg,png,webp|max:3072',
            'is_active' => 'nullable|boolean',
        ]);

        $data = [
            'name' => strip_tags($validated['name']),
            'description' => strip_tags($validated['description']),
            'category_id' => $validated['category_id'],
            'material' => strip_tags($validated['material']),
            'price_estimate' => $validated['price_estimate'],
            'is_active' => $validated['is_active'] ?? true,
        ];

        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')
                ->store('catalog-images', 'public');
        }

        $product->update($data);

        return redirect()
            ->route('admin.catalog.index')
            ->with('success', 'Produk katalog berhasil diperbarui.');
    }

    /**
     * Soft-delete a catalog product (set is_active=false).
     */
    public function destroy(string $id)
    {
        $product = CatalogProduct::findOrFail($id);
        $product->update(['is_active' => false]);

        return redirect()
            ->route('admin.catalog.index')
            ->with('success', 'Produk berhasil dinonaktifkan.');
    }
}

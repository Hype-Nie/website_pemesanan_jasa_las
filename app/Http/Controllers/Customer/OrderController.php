<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\CatalogProduct;
use App\Models\CustomOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * List the authenticated user's orders with optional status filter.
     */
    public function index(Request $request)
    {
        $query = Auth::user()->customOrders()->with('catalogProduct');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query->latest()->paginate(10);

        return view('customer.orders.index', compact('orders'));
    }

    /**
     * Show the order creation form, optionally pre-filled from catalog.
     */
    public function create(string $catalogProductId = null)
    {
        $catalogProduct = null;

        if ($catalogProductId) {
            $catalogProduct = CatalogProduct::active()->findOrFail($catalogProductId);
        }

        return view('customer.orders.create', compact('catalogProduct'));
    }

    /**
     * Validate and store a new custom order.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_name' => 'required|string|max:255',
            'description' => 'required|string',
            'dimensions' => 'required|string|max:100',
            'material_preference' => 'nullable|string|max:100',
            'quantity' => 'required|integer|min:1',
            'catalog_product_id' => 'nullable|exists:catalog_products,id',
            'reference_design' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        $data = [
            'user_id' => Auth::id(),
            'product_name' => strip_tags($validated['product_name']),
            'description' => strip_tags($validated['description']),
            'dimensions' => strip_tags($validated['dimensions']),
            'material_preference' => isset($validated['material_preference']) ? strip_tags($validated['material_preference']) : null,
            'quantity' => $validated['quantity'],
            'catalog_product_id' => $validated['catalog_product_id'] ?? null,
        ];

        if ($request->hasFile('reference_design')) {
            $data['reference_design_path'] = $request->file('reference_design')
                ->store('order-designs', 'public');
        }

        $order = CustomOrder::create($data);

        return redirect()
            ->route('customer.orders.show', $order->id)
            ->with('success', 'Pesanan berhasil dibuat! Kode pesanan Anda: ' . $order->order_code);
    }

    /**
     * Show order detail (authorized: user must own the order).
     */
    public function show(string $id)
    {
        $order = CustomOrder::with(['catalogProduct', 'payments'])
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        return view('customer.orders.show', compact('order'));
    }

    /**
     * Public tracking by order code.
     */
    public function track(Request $request)
    {
        $order = null;

        if ($request->filled('order_code')) {
            $orderCode = strip_tags($request->order_code);
            $order = CustomOrder::where('order_code', $orderCode)->first();
        }

        return view('track', compact('order'));
    }
}

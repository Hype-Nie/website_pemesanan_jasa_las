<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CustomOrder;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * List all orders with status/search filter, paginated.
     */
    public function index(Request $request)
    {
        $query = CustomOrder::with('user');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_code', 'like', "%{$search}%")
                  ->orWhere('product_name', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $orders = $query->latest()->paginate(15);

        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Show order detail with payments.
     */
    public function show(string $id)
    {
        $order = CustomOrder::with(['user', 'catalogProduct', 'payments'])
            ->findOrFail($id);

        return view('admin.orders.show', compact('order'));
    }

    /**
     * Update order status, admin notes, and total price.
     */
    public function updateStatus(Request $request, string $id)
    {
        $order = CustomOrder::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|string|in:pending,confirmed,in_production,completed,cancelled',
            'admin_notes' => 'nullable|string',
            'total_price' => 'nullable|numeric|min:0',
        ]);

        if ($validated['status'] === 'in_production') {
            $hasVerifiedPayment = $order->payments()->where('status', 'verified')->exists();
            
            if (!$hasVerifiedPayment) {
                return back()->with('error', 'Status tidak dapat diubah ke "Dalam Pengerjaan" karena belum ada bukti pembayaran yang disetujui.');
            }
        }

        $order->update([
            'status' => $validated['status'],
            'admin_notes' => isset($validated['admin_notes']) ? strip_tags($validated['admin_notes']) : $order->admin_notes,
            'total_price' => $validated['total_price'] ?? $order->total_price,
        ]);

        return redirect()
            ->route('admin.orders.show', $order->id)
            ->with('success', 'Status pesanan berhasil diperbarui.');
    }
}

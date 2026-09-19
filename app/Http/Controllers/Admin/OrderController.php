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
            'dp_amount' => 'nullable|numeric|min:0',
        ]);

        if ($validated['status'] === 'in_production') {
            if (!$order->isDpPaid()) {
                return back()->with('error', 'Status tidak dapat diubah ke "Dalam Pengerjaan" karena Down Payment (DP) belum dibayar/diverifikasi.');
            }
        }

        if ($validated['status'] === 'completed' && $order->status !== 'completed') {
            if ((int) $order->progress_percentage < 100) {
                return back()->with('error', 'Status tidak dapat diubah ke "Selesai" karena proses pengerjaan teknisi belum mencapai 100% (saat ini: ' . (int) $order->progress_percentage . '%).');
            }

            if (!$order->isFullyPaid()) {
                return back()->with('error', 'Status tidak dapat diubah ke "Selesai" karena sisa pembayaran pesanan belum lunas (100%).');
            }
        }

        $order->update([
            'status' => $validated['status'],
            'admin_notes' => isset($validated['admin_notes']) ? strip_tags($validated['admin_notes']) : $order->admin_notes,
            'total_price' => $validated['total_price'] ?? $order->total_price,
            'dp_amount' => array_key_exists('dp_amount', $validated) && $validated['dp_amount'] !== null ? $validated['dp_amount'] : $order->dp_amount,
        ]);

        return redirect()
            ->route('admin.orders.show', $order->id)
            ->with('success', 'Status pesanan berhasil diperbarui.');
    }
}

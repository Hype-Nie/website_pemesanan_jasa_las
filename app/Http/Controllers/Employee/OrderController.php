<?php

namespace App\Http\Controllers\Employee;

use App\Enums\WorkProgress;
use App\Http\Controllers\Controller;
use App\Models\CustomOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class OrderController extends Controller
{
    /**
     * Display approved orders that can be worked on by employees.
     */
    public function index(Request $request)
    {
        $query = CustomOrder::with(['user', 'catalogProduct'])
            ->whereIn('status', ['confirmed', 'in_production', 'completed']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('progress')) {
            $query->where('progress_percentage', $request->progress);
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
        $progressOptions = WorkProgress::options();

        return view('employee.orders.index', compact('orders', 'progressOptions'));
    }

    /**
     * Show approved order details for production.
     */
    public function show(string $id)
    {
        $order = CustomOrder::with(['user', 'catalogProduct', 'payments'])
            ->whereIn('status', ['confirmed', 'in_production', 'completed'])
            ->findOrFail($id);

        $progressOptions = WorkProgress::cases();

        return view('employee.orders.show', compact('order', 'progressOptions'));
    }

    /**
     * Update order work status using percentage-based Enum.
     */
    public function updateProgress(Request $request, string $id)
    {
        $order = CustomOrder::whereIn('status', ['confirmed', 'in_production', 'completed'])
            ->findOrFail($id);

        if (!$order->isDpPaid() && !$order->isFullyPaid()) {
            return back()->with('error', 'Progres tidak dapat diperbarui karena pelanggan belum membayar Down Payment (DP) atau pembayaran belum diverifikasi.');
        }

        $validated = $request->validate([
            'progress_percentage' => 'required|integer|in:0,25,50,75,100',
            'progress_notes' => 'nullable|string|max:1000',
            'progress_photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
        ]);

        $updateData = [
            'progress_percentage' => (int) $validated['progress_percentage'],
            'progress_notes' => isset($validated['progress_notes']) ? strip_tags($validated['progress_notes']) : $order->progress_notes,
        ];

        if ($request->hasFile('progress_photo')) {
            if ($order->progress_photo_path && Storage::disk('public')->exists($order->progress_photo_path)) {
                Storage::disk('public')->delete($order->progress_photo_path);
            }
            $updateData['progress_photo_path'] = $request->file('progress_photo')->store('progress-photos', 'public');
        }

        // Automatically advance status to in_production if previously confirmed and work has started
        if ($order->status === 'confirmed' && (int) $validated['progress_percentage'] > 0) {
            $updateData['status'] = 'in_production';
        }

        $order->update($updateData);

        return redirect()
            ->route('employee.orders.show', $order->id)
            ->with('success', 'Progres pengerjaan berhasil diperbarui menjadi ' . $order->workProgress()->label() . '.');
    }
}

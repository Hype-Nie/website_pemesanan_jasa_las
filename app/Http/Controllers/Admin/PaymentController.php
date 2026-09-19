<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    /**
     * List all payments pending verification.
     */
    public function index()
    {
        $payments = Payment::with(['customOrder.user'])
            ->where('status', 'pending')
            ->latest()
            ->paginate(15);

        return view('admin.payments.index', compact('payments'));
    }

    /**
     * Mark payment as verified and update order status accordingly.
     */
    public function verify(string $id)
    {
        $payment = Payment::with('customOrder')->findOrFail($id);

        DB::transaction(function () use ($payment) {
            $payment->update([
                'status' => 'verified',
                'verified_at' => Carbon::now(),
            ]);

            $order = $payment->customOrder;
            if ($order) {
                // If Down Payment verified and order was confirmed, auto transition to in_production
                if ($payment->isDownPayment() && $order->status === 'confirmed') {
                    $order->update([
                        'status' => 'in_production',
                    ]);
                }
            }
        });

        $typeLabel = $payment->isDownPayment() ? 'Down Payment (DP)' : 'Pelunasan';

        return redirect()
            ->route('admin.payments.index')
            ->with('success', "Pembayaran {$typeLabel} untuk pesanan {$payment->customOrder->order_code} berhasil diverifikasi.");
    }

    /**
     * Mark payment as rejected with admin notes.
     */
    public function reject(Request $request, string $id)
    {
        $payment = Payment::with('customOrder')->findOrFail($id);

        $validated = $request->validate([
            'admin_notes' => 'required|string|max:500',
        ]);

        $payment->update([
            'status' => 'rejected',
            'admin_notes' => strip_tags($validated['admin_notes']),
        ]);

        return redirect()
            ->route('admin.payments.index')
            ->with('success', 'Pembayaran ditolak.');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

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
     * Mark payment as verified.
     */
    public function verify(string $id)
    {
        $payment = Payment::findOrFail($id);

        $payment->update([
            'status' => 'verified',
            'verified_at' => Carbon::now(),
        ]);

        return redirect()
            ->route('admin.payments.index')
            ->with('success', 'Pembayaran berhasil diverifikasi.');
    }

    /**
     * Mark payment as rejected with admin notes.
     */
    public function reject(Request $request, string $id)
    {
        $payment = Payment::findOrFail($id);

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

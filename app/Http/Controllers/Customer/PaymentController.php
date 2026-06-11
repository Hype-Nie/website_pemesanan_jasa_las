<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\CustomOrder;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    /**
     * Show the payment upload form for a specific order.
     */
    public function create(string $orderId)
    {
        $order = CustomOrder::where('user_id', Auth::id())
            ->where('status', '!=', 'cancelled')
            ->findOrFail($orderId);

        return view('customer.payments.create', compact('order'));
    }

    /**
     * Validate and store payment proof.
     */
    public function store(Request $request, string $orderId)
    {
        $order = CustomOrder::where('user_id', Auth::id())
            ->where('status', '!=', 'cancelled')
            ->findOrFail($orderId);

        $validated = $request->validate([
            'amount' => 'required|numeric|min:1',
            'proof_image' => 'required|file|mimes:jpg,jpeg,png|max:3072',
            'bank_name' => 'nullable|string|max:100',
            'account_name' => 'nullable|string|max:100',
            'payment_method' => 'nullable|string|max:50',
        ]);

        $proofPath = $request->file('proof_image')
            ->store('payment-proofs', 'public');

        Payment::create([
            'custom_order_id' => $order->id,

            'amount' => $validated['amount'],
            'payment_method' => isset($validated['payment_method']) ? strip_tags($validated['payment_method']) : 'bank_transfer',
            'proof_image_path' => $proofPath,
            'bank_name' => isset($validated['bank_name']) ? strip_tags($validated['bank_name']) : null,
            'account_name' => isset($validated['account_name']) ? strip_tags($validated['account_name']) : null,
        ]);

        return redirect()
            ->route('customer.orders.show', $order->id)
            ->with('success', 'Bukti pembayaran berhasil dikirim. Menunggu verifikasi admin.');
    }
}

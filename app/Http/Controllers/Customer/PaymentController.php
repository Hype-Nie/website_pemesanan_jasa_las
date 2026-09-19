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
        $order = CustomOrder::with('payments')
            ->where('user_id', Auth::id())
            ->where('status', '!=', 'cancelled')
            ->findOrFail($orderId);

        if (!$order->total_price) {
            return redirect()
                ->route('customer.orders.show', $order->id)
                ->with('error', 'Pesanan ini belum memiliki total harga dari admin. Pembayaran belum dapat dilakukan.');
        }

        if ($order->isFullyPaid()) {
            return redirect()
                ->route('customer.orders.show', $order->id)
                ->with('info', 'Pesanan ini sudah lunas.');
        }

        // Determine required payment stage: DP must come first
        $isDpPaid = $order->isDpPaid();
        $targetPaymentType = $isDpPaid ? 'full_payment' : 'down_payment';

        // Check if there is already a pending payment of this type
        $hasPending = $order->payments()
            ->where('payment_type', $targetPaymentType)
            ->where('status', 'pending')
            ->exists();

        if ($hasPending) {
            $typeLabel = $targetPaymentType === 'down_payment' ? 'Down Payment (DP)' : 'Pelunasan';
            return redirect()
                ->route('customer.orders.show', $order->id)
                ->with('warning', 'Anda sudah mengunggah bukti ' . $typeLabel . ' yang sedang menunggu verifikasi admin.');
        }

        $expectedAmount = $targetPaymentType === 'down_payment' ? $order->requiredDpAmount() : $order->remainingBalance();

        return view('customer.payments.create', compact('order', 'targetPaymentType', 'expectedAmount'));
    }

    /**
     * Validate and store payment proof with down_payment or full_payment stage check.
     */
    public function store(Request $request, string $orderId)
    {
        $order = CustomOrder::with('payments')
            ->where('user_id', Auth::id())
            ->where('status', '!=', 'cancelled')
            ->findOrFail($orderId);

        $validated = $request->validate([
            'payment_type' => 'nullable|in:down_payment,full_payment',
            'amount' => 'required|numeric|min:1',
            'proof_image' => 'required|file|mimes:jpg,jpeg,png|max:3072',
            'bank_name' => 'nullable|string|max:100',
            'account_name' => 'nullable|string|max:100',
            'payment_method' => 'nullable|string|max:50',
        ]);

        $paymentType = $validated['payment_type'] ?? ($order->isDpPaid() ? 'full_payment' : 'down_payment');

        // Enforce sequence: DP must be verified first before full payment is allowed
        if ($paymentType === 'full_payment' && !$order->isDpPaid()) {
            return back()
                ->withInput()
                ->with('error', 'Anda wajib membayar Down Payment (DP) terlebih dahulu sebelum melakukan pelunasan.');
        }

        if ($paymentType === 'down_payment' && $order->isDpPaid() && (float)($order->total_price ?? 0) > 0) {
            return back()
                ->withInput()
                ->with('error', 'Down Payment (DP) untuk pesanan ini sudah diverifikasi.');
        }

        // Prevent duplicate pending payment for the same type
        $alreadyPending = $order->payments()
            ->where('payment_type', $paymentType)
            ->where('status', 'pending')
            ->exists();

        if ($alreadyPending) {
            return back()
                ->withInput()
                ->with('error', 'Bukti pembayaran jenis ini sudah diunggah dan sedang dalam proses verifikasi.');
        }

        $proofPath = $request->file('proof_image')
            ->store('payment-proofs', 'public');

        Payment::create([
            'custom_order_id' => $order->id,
            'payment_type' => $paymentType,
            'amount' => $validated['amount'],
            'payment_method' => isset($validated['payment_method']) ? strip_tags($validated['payment_method']) : 'bank_transfer',
            'proof_image_path' => $proofPath,
            'bank_name' => isset($validated['bank_name']) ? strip_tags($validated['bank_name']) : null,
            'account_name' => isset($validated['account_name']) ? strip_tags($validated['account_name']) : null,
            'status' => 'pending',
        ]);

        $label = $paymentType === 'down_payment' ? 'Down Payment (DP)' : 'Pelunasan';

        return redirect()
            ->route('customer.orders.show', $order->id)
            ->with('success', 'Bukti pembayaran ' . $label . ' berhasil dikirim. Menunggu verifikasi admin.');
    }
}

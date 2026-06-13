<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CustomOrder;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    /**
     * Parse date range dari request.
     */
    private function parseDates(Request $request): array
    {
        $from = $request->input('from', now()->startOfMonth()->format('Y-m-d'));
        $to   = $request->input('to',   now()->endOfMonth()->format('Y-m-d'));

        // Pastikan format valid
        try {
            $from = \Carbon\Carbon::parse($from)->startOfDay();
            $to   = \Carbon\Carbon::parse($to)->endOfDay();
        } catch (\Throwable $e) {
            $from = now()->startOfMonth()->startOfDay();
            $to   = now()->endOfMonth()->endOfDay();
        }

        return [$from, $to];
    }

    /**
     * Query transaksi dalam rentang tanggal.
     */
    private function getTransactions($from, $to)
    {
        return CustomOrder::with(['user', 'payment'])
            ->whereBetween('created_at', [$from, $to])
            ->latest()
            ->get();
    }

    /**
     * Laporan Transaksi Admin.
     */
    public function index(Request $request)
    {
        [$from, $to] = $this->parseDates($request);

        $transactions = $this->getTransactions($from, $to);

        $totalOrders    = $transactions->count();
        $totalRevenue   = $transactions
            ->filter(fn($o) => $o->payment && $o->payment->status === 'verified')
            ->sum(fn($o) => $o->payment->amount ?? 0);
        $totalPending   = $transactions->where('status', 'pending')->count();
        $totalCompleted = $transactions->where('status', 'completed')->count();

        $statusSummary = $transactions->groupBy('status')->map->count();

        return view('admin.reports.index', compact(
            'from', 'to',
            'totalOrders', 'totalRevenue',
            'totalPending', 'totalCompleted',
            'transactions', 'statusSummary'
        ));
    }

    /**
     * Export ke CSV.
     */
    public function exportCsv(Request $request)
    {
        [$from, $to] = $this->parseDates($request);
        $transactions = $this->getTransactions($from, $to);

        $filename = 'laporan_' . $from->format('Y-m-d') . '_sd_' . $to->format('Y-m-d') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($transactions) {
            $handle = fopen('php://output', 'w');
            // BOM for Excel UTF-8
            fputs($handle, "\xEF\xBB\xBF");

            fputcsv($handle, [
                'No', 'Tanggal', 'Pelanggan', 'No. HP', 'Produk',
                'Harga Final (Rp)', 'Status Pesanan', 'Status Bayar',
                'Jumlah Dibayar (Rp)', 'Metode Bayar',
            ]);

            foreach ($transactions as $i => $order) {
                fputcsv($handle, [
                    $i + 1,
                    $order->created_at->format('d/m/Y'),
                    $order->user->name ?? '-',
                    $order->user->phone ?? '-',
                    $order->product_name ?? '-',
                    $order->total_price ?? 0,
                    ucfirst($order->status),
                    $order->payment ? ucfirst($order->payment->status) : 'Belum Bayar',
                    $order->payment ? $order->payment->amount : 0,
                    $order->payment->payment_method ?? '-',
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export ke PDF.
     */
    public function exportPdf(Request $request)
    {
        [$from, $to] = $this->parseDates($request);
        $transactions = $this->getTransactions($from, $to);

        $totalRevenue = $transactions
            ->filter(fn($o) => $o->payment && $o->payment->status === 'verified')
            ->sum(fn($o) => $o->payment->amount ?? 0);

        $pdf = Pdf::loadView('admin.reports.pdf', compact('transactions', 'from', 'to', 'totalRevenue'))
            ->setPaper('a4', 'landscape');

        $filename = 'laporan_' . $from->format('Y-m-d') . '_sd_' . $to->format('Y-m-d') . '.pdf';

        return $pdf->download($filename);
    }
}

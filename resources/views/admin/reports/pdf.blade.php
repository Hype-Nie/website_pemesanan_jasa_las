<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 10px; color: #333; }
        .header { background: #1a1a2e; color: white; padding: 16px 20px; margin-bottom: 20px; }
        .header h1 { font-size: 18px; font-weight: bold; }
        .header p  { font-size: 11px; opacity: 0.8; margin-top: 4px; }
        .meta { display: flex; gap: 40px; padding: 0 20px 12px; }
        .meta-item label { font-size: 9px; color: #888; text-transform: uppercase; }
        .meta-item strong { display: block; font-size: 13px; color: #1a1a2e; }
        .stat-row { display: flex; gap: 12px; padding: 0 20px 16px; }
        .stat-box { flex: 1; background: #f8f9fa; border-left: 4px solid #FD5D14; padding: 10px 14px; border-radius: 4px; }
        .stat-box .label { font-size: 9px; color: #666; text-transform: uppercase; }
        .stat-box .value { font-size: 15px; font-weight: bold; color: #1a1a2e; margin-top: 2px; }
        table { width: 100%; border-collapse: collapse; margin: 0 20px; width: calc(100% - 40px); }
        thead tr { background: #1a1a2e; color: white; }
        thead th { padding: 8px 10px; text-align: left; font-size: 9px; text-transform: uppercase; letter-spacing: 0.5px; }
        tbody tr:nth-child(even) { background: #f8f9fa; }
        tbody td { padding: 7px 10px; border-bottom: 1px solid #eee; font-size: 9.5px; }
        .badge { padding: 2px 7px; border-radius: 10px; font-size: 8.5px; font-weight: bold; }
        .badge-warning  { background: #fff3cd; color: #856404; }
        .badge-info     { background: #cff4fc; color: #0c5460; }
        .badge-success  { background: #d1e7dd; color: #0a3622; }
        .badge-danger   { background: #f8d7da; color: #842029; }
        .badge-secondary{ background: #e2e3e5; color: #383d41; }
        .footer { margin-top: 16px; padding: 10px 20px; font-size: 9px; color: #999; border-top: 1px solid #eee; text-align: right; }
        .total-row { background: #1a1a2e !important; color: white; font-weight: bold; }
        .total-row td { color: white; border: none; padding: 9px 10px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>🔥 BENGKEL ASYRAF — Laporan Transaksi</h1>
        <p>Periode: {{ $from->format('d F Y') }} s.d. {{ $to->format('d F Y') }}</p>
    </div>

    <div class="stat-row">
        <div class="stat-box">
            <div class="label">Total Pesanan</div>
            <div class="value">{{ $transactions->count() }}</div>
        </div>
        <div class="stat-box">
            <div class="label">Total Pendapatan</div>
            <div class="value">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
        </div>
        <div class="stat-box">
            <div class="label">Selesai</div>
            <div class="value">{{ $transactions->where('status', 'selesai')->count() }}</div>
        </div>
        <div class="stat-box">
            <div class="label">Menunggu</div>
            <div class="value">{{ $transactions->where('status', 'pending')->count() }}</div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th width="25">#</th>
                <th width="60">Tanggal</th>
                <th>Pelanggan</th>
                <th>Produk</th>
                <th width="90">Harga Final</th>
                <th width="70">Status</th>
                <th width="70">Pembayaran</th>
                <th width="90">Jumlah Dibayar</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transactions as $i => $order)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $order->created_at->format('d/m/Y') }}</td>
                    <td>
                        {{ $order->user->name ?? '-' }}
                        @if($order->user->phone ?? null)
                            <br><span style="color:#888;font-size:8.5px">{{ $order->user->phone }}</span>
                        @endif
                    </td>
                    <td>{{ \Illuminate\Support\Str::limit($order->product_name ?? '-', 40) }}</td>
                    <td>
                        @if($order->total_price)
                            Rp {{ number_format($order->total_price, 0, ',', '.') }}
                        @else
                            <span style="color:#999">-</span>
                        @endif
                    </td>
                    <td>
                        @php
                            $statusMap = ['pending'=>['warning','Menunggu'],'diproses'=>['info','Diproses'],'selesai'=>['success','Selesai'],'ditolak'=>['danger','Ditolak']];
                            [$sc,$sl] = $statusMap[$order->status] ?? ['secondary', ucfirst($order->status)];
                        @endphp
                        <span class="badge badge-{{ $sc }}">{{ $sl }}</span>
                    </td>
                    <td>
                        @if($order->payment)
                            @php [$pc,$pl] = [['pending'=>'warning','verified'=>'success','rejected'=>'danger'][$order->payment->status] ?? 'secondary', ['pending'=>'Menunggu','verified'=>'Verified','rejected'=>'Ditolak'][$order->payment->status] ?? $order->payment->status]; @endphp
                            <span class="badge badge-{{ $pc }}">{{ $pl }}</span>
                        @else
                            <span style="color:#999">Belum</span>
                        @endif
                    </td>
                    <td>
                        @if($order->payment)
                            Rp {{ number_format($order->payment->amount, 0, ',', '.') }}
                        @else
                            -
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="8" style="text-align:center;padding:20px;color:#999">Tidak ada transaksi</td></tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="4" style="text-align:right">TOTAL PENDAPATAN TERVERIFIKASI</td>
                <td colspan="4">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        Dicetak pada {{ now()->format('d F Y, H:i') }} WIB &bull; Bengkel Asyraf - Jasa Las Talaga, Bone
    </div>
</body>
</html>

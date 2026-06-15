@extends('layouts.admin')

@section('title', 'Laporan Transaksi - Admin Bengkel Asyraf')
@section('page-title', 'Laporan Transaksi')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style>
    .flatpickr-input { background: #fff !important; cursor: pointer; }
    .date-range-card { background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%); border-radius: 12px; }
    .export-btn { transition: all .2s; }
    .export-btn:hover { transform: translateY(-1px); box-shadow: 0 4px 12px rgba(0,0,0,.15); }
    .stat-icon-box { width: 52px; height: 52px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; flex-shrink: 0; }
    .report-table th { font-size: .8rem; text-transform: uppercase; letter-spacing: .5px; color: #6c757d; font-weight: 600; }
    .status-badge { padding: .3em .7em; border-radius: 20px; font-size: .78rem; font-weight: 600; }
</style>
@endpush

@section('content')

{{-- ═══════════════════ FILTER CARD ═══════════════════ --}}
<div class="card p-4 mb-4 shadow-sm border-0">
    <div class="d-flex flex-wrap align-items-end gap-3">
        <div>
            <label class="form-label text-muted mb-1 small text-uppercase fw-bold">Dari Tanggal</label>
            <input type="text" id="from_date" name="from" class="form-control bg-light"
                value="{{ $from->format('Y-m-d') }}" placeholder="Dari..." style="min-width:160px;">
        </div>
        <div>
            <label class="form-label text-muted mb-1 small text-uppercase fw-bold">Sampai Tanggal</label>
            <input type="text" id="to_date" name="to" class="form-control bg-light"
                value="{{ $to->format('Y-m-d') }}" placeholder="Sampai..." style="min-width:160px;">
        </div>
        <button id="btnFilter" class="btn btn-primary fw-bold px-4 export-btn">
            <i class="fas fa-search me-2"></i>Tampilkan
        </button>
        {{-- Quick presets --}}
        <div class="ms-auto d-flex flex-wrap gap-2">
            <button class="btn btn-outline-secondary btn-sm preset-btn" data-preset="today">Hari Ini</button>
            <button class="btn btn-outline-secondary btn-sm preset-btn" data-preset="this_week">Minggu Ini</button>
            <button class="btn btn-outline-secondary btn-sm preset-btn" data-preset="this_month">Bulan Ini</button>
            <button class="btn btn-outline-secondary btn-sm preset-btn" data-preset="last_month">Bulan Lalu</button>
            <button class="btn btn-outline-secondary btn-sm preset-btn" data-preset="this_year">Tahun Ini</button>
        </div>
    </div>
    <div class="mt-3 pt-3 border-top d-flex flex-wrap align-items-center gap-3">
        <span class="text-muted small">
            <i class="fas fa-calendar-alt me-1 text-primary"></i>
            Periode aktif: <strong class="text-dark">{{ $from->format('d F Y') }}</strong> s.d. <strong class="text-dark">{{ $to->format('d F Y') }}</strong>
        </span>
        <div class="ms-auto d-flex gap-2">
            <a href="{{ route('admin.reports.export.csv', ['from' => $from->format('Y-m-d'), 'to' => $to->format('Y-m-d')]) }}"
               class="btn btn-success btn-sm export-btn fw-semibold">
                <i class="fas fa-file-csv me-2"></i>Export CSV
            </a>
            <a href="{{ route('admin.reports.export.pdf', ['from' => $from->format('Y-m-d'), 'to' => $to->format('Y-m-d')]) }}"
               class="btn btn-danger btn-sm export-btn fw-semibold" target="_blank">
                <i class="fas fa-file-pdf me-2"></i>Export PDF
            </a>
        </div>
    </div>
</div>

{{-- ═══════════════════ STAT CARDS ═══════════════════ --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="card stat-card border-0 h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon-box" style="background:rgba(253,93,20,.12)">
                    <i class="fas fa-clipboard-list text-primary"></i>
                </div>
                <div>
                    <div class="text-muted" style="font-size:.8rem">Total Pesanan</div>
                    <div class="fw-bold fs-3 lh-1 mt-1">{{ $totalOrders }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card stat-card border-0 h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon-box" style="background:rgba(25,135,84,.12)">
                    <i class="fas fa-money-bill-wave text-success"></i>
                </div>
                <div>
                    <div class="text-muted" style="font-size:.8rem">Total Pendapatan</div>
                    <div class="fw-bold fs-5 lh-1 mt-1">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card stat-card border-0 h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon-box" style="background:rgba(255,193,7,.12)">
                    <i class="fas fa-hourglass-half text-warning"></i>
                </div>
                <div>
                    <div class="text-muted" style="font-size:.8rem">Menunggu Review</div>
                    <div class="fw-bold fs-3 lh-1 mt-1">{{ $totalPending }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card stat-card border-0 h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon-box" style="background:rgba(13,110,253,.12)">
                    <i class="fas fa-check-circle text-info"></i>
                </div>
                <div>
                    <div class="text-muted" style="font-size:.8rem">Selesai</div>
                    <div class="fw-bold fs-3 lh-1 mt-1">{{ $totalCompleted }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ═══════════════════ TABLE + SUMMARY ═══════════════════ --}}
<div class="row g-4">
    <div class="col-lg-8">
        <div class="card stat-card">
            <div class="card-header d-flex justify-content-between align-items-center py-3">
                <h5 class="mb-0 fw-bold"><i class="fas fa-table me-2 text-primary"></i>Rekap Transaksi</h5>
                <span class="badge bg-primary rounded-pill">{{ $totalOrders }} data</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 report-table">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3">#</th>
                                <th>Pelanggan</th>
                                <th>Produk</th>
                                <th>Harga Final</th>
                                <th>Status</th>
                                <th>Bayar</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transactions as $i => $order)
                                <tr>
                                    <td class="ps-3 text-muted small">{{ $i + 1 }}</td>
                                    <td>
                                        <span class="fw-semibold">{{ $order->user->name ?? '-' }}</span>
                                        <br><small class="text-muted">{{ $order->user->phone ?? '' }}</small>
                                    </td>
                                    <td>{{ Str::limit($order->product_name, 28) }}</td>
                                    <td>
                                        @if($order->total_price)
                                            <span class="fw-semibold text-success">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                                        @else
                                            <span class="text-muted fst-italic small">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $sm = [
                                                'pending'       => ['warning text-dark', 'Menunggu'],
                                                'confirmed'     => ['info text-white', 'Dikonfirmasi'],
                                                'in_production' => ['primary', 'Diproses'],
                                                'completed'     => ['success', 'Selesai'],
                                                'cancelled'     => ['danger', 'Dibatalkan']
                                            ];
                                            [$sc,$sl] = $sm[$order->status] ?? ['secondary', ucfirst($order->status)];
                                        @endphp
                                        <span class="badge bg-{{ $sc }}">{{ $sl }}</span>
                                    </td>
                                    <td>
                                        @if($order->payment)
                                            @php
                                                $pm = ['pending'=>['warning text-dark','Menunggu'],'verified'=>['success','Lunas'],'rejected'=>['danger','Ditolak']];
                                                [$pc,$pl] = $pm[$order->payment->status] ?? ['secondary', $order->payment->status];
                                            @endphp
                                            <span class="badge bg-{{ $pc }}">{{ $pl }}</span>
                                        @else
                                            <span class="text-muted small">Belum</span>
                                        @endif
                                    </td>
                                    <td class="text-muted small">{{ $order->created_at->format('d/m/Y') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5 text-muted">
                                        <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                                        Tidak ada transaksi pada periode ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                        @if($totalOrders > 0)
                            <tfoot>
                                <tr class="table-dark fw-bold">
                                    <td colspan="3" class="ps-3 text-end">Total Pendapatan Terverifikasi</td>
                                    <td class="text-success">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</td>
                                    <td colspan="3"></td>
                                </tr>
                            </tfoot>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Rekap Sidebar --}}
    <div class="col-lg-4">
        <div class="card stat-card mb-3">
            <div class="card-header py-3">
                <h6 class="mb-0 fw-bold"><i class="fas fa-chart-pie me-2 text-primary"></i>Rekap per Status</h6>
            </div>
            <div class="card-body">
                @php
                    $allStatuses = [
                        'pending'       => ['FFC107', 'text-dark', 'Menunggu Review'],
                        'confirmed'     => ['0DCAF0', 'text-white', 'Dikonfirmasi'],
                        'in_production' => ['0DCAF0', 'text-white', 'Sedang Diproses'],
                        'completed'     => ['198754', 'text-white', 'Selesai'],
                        'cancelled'     => ['DC3545', 'text-white', 'Dibatalkan']
                    ];
                @endphp
                @foreach($allStatuses as $key => [$hex, $txtcls, $label])
                    @php $cnt = $statusSummary[$key] ?? 0; $pct = $totalOrders > 0 ? round($cnt / $totalOrders * 100) : 0; @endphp
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="small fw-semibold">{{ $label }}</span>
                            <span class="small fw-bold">{{ $cnt }} <span class="text-muted">({{ $pct }}%)</span></span>
                        </div>
                        <div class="progress" style="height:6px;border-radius:3px">
                            <div class="progress-bar" style="width:{{ $pct }}%;background:#{{ $hex }};border-radius:3px"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="card stat-card border-primary border-2">
            <div class="card-body">
                <h6 class="fw-bold mb-3"><i class="fas fa-receipt me-2 text-primary"></i>Ringkasan Periode</h6>
                <div class="d-flex justify-content-between mb-2 pb-2 border-bottom">
                    <span class="text-muted">Total Pesanan</span>
                    <strong>{{ $totalOrders }}</strong>
                </div>
                <div class="d-flex justify-content-between mb-2 pb-2 border-bottom">
                    <span class="text-muted">Sudah Selesai</span>
                    <strong class="text-success">{{ $totalCompleted }}</strong>
                </div>
                <div class="d-flex justify-content-between mb-2 pb-2 border-bottom">
                    <span class="text-muted">Masih Pending</span>
                    <strong class="text-warning">{{ $totalPending }}</strong>
                </div>
                <div class="d-flex justify-content-between mt-1">
                    <span class="fw-bold">Pendapatan Verified</span>
                    <strong class="text-success fs-6">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</strong>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/id.js"></script>
<script>
    const fpFrom = flatpickr('#from_date', {
        locale: 'id',
        dateFormat: 'Y-m-d',
        altInput: true,
        altFormat: 'd F Y',
        maxDate: 'today',
        onChange: function(sel) {
            fpTo.set('minDate', sel[0]);
        }
    });

    const fpTo = flatpickr('#to_date', {
        locale: 'id',
        dateFormat: 'Y-m-d',
        altInput: true,
        altFormat: 'd F Y',
        maxDate: 'today',
        onChange: function(sel) {
            fpFrom.set('maxDate', sel[0]);
        }
    });

    document.getElementById('btnFilter').addEventListener('click', function() {
        const from = document.getElementById('from_date').value;
        const to   = document.getElementById('to_date').value;
        if (!from || !to) return alert('Pilih tanggal dari dan sampai terlebih dahulu.');
        window.location.href = '{{ route('admin.reports.index') }}?from=' + from + '&to=' + to;
    });

    // Quick preset buttons
    document.querySelectorAll('.preset-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const preset = this.dataset.preset;
            let from, to;
            const now = new Date();

            if (preset === 'today') {
                from = to = formatDate(now);
            } else if (preset === 'this_week') {
                const day = now.getDay() || 7;
                const mon = new Date(now); mon.setDate(now.getDate() - day + 1);
                from = formatDate(mon); to = formatDate(now);
            } else if (preset === 'this_month') {
                from = formatDate(new Date(now.getFullYear(), now.getMonth(), 1));
                to   = formatDate(new Date(now.getFullYear(), now.getMonth() + 1, 0));
            } else if (preset === 'last_month') {
                from = formatDate(new Date(now.getFullYear(), now.getMonth() - 1, 1));
                to   = formatDate(new Date(now.getFullYear(), now.getMonth(), 0));
            } else if (preset === 'this_year') {
                from = formatDate(new Date(now.getFullYear(), 0, 1));
                to   = formatDate(new Date(now.getFullYear(), 11, 31));
            }

            window.location.href = '{{ route('admin.reports.index') }}?from=' + from + '&to=' + to;
        });
    });

    function formatDate(d) {
        return d.getFullYear() + '-' + String(d.getMonth()+1).padStart(2,'0') + '-' + String(d.getDate()).padStart(2,'0');
    }
</script>
@endpush

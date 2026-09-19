@extends('layouts.employee')

@section('title', 'Antrean Pengerjaan - Panel Karyawan')
@section('page-title', 'Antrean Pengerjaan Las')

@section('content')
<div class="container-fluid p-0">
    <!-- Header Summary -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div>
            <h5 class="fw-bold text-dark mb-1">
                <i class="fas fa-clipboard-list text-warning me-2"></i>Daftar Seluruh Antrean Pengerjaan
            </h5>
            <p class="text-muted small mb-0">Pesanan yang telah diverifikasi dan siap/sedang diproduksi di bengkel.</p>
        </div>
        <div class="mt-2 mt-md-0">
            <a href="{{ route('employee.dashboard') }}" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-tachometer-alt me-1"></i> Kembali ke Dashboard
            </a>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body bg-white p-3 p-md-4 rounded">
            <form method="GET" action="{{ route('employee.orders.index') }}" class="row g-2 g-md-3">
                <div class="col-md-4">
                    <label class="form-label small fw-bold text-muted mb-1">Pencarian</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="fas fa-search text-muted"></i></span>
                        <input type="text" class="form-control border-start-0" name="search"
                            value="{{ request('search') }}" placeholder="Kode pesanan, nama produk, pemesan...">
                    </div>
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-bold text-muted mb-1">Status Pesanan</label>
                    <select class="form-select" name="status">
                        <option value="">Semua Status Pesanan</option>
                        <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Dikonfirmasi (Siap Dikerjakan)</option>
                        <option value="in_production" {{ request('status') == 'in_production' ? 'selected' : '' }}>Sedang Dikerjakan</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Pekerjaan Selesai</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-bold text-muted mb-1">Tahap Progres</label>
                    <select class="form-select" name="progress">
                        <option value="">Semua Tahap Progres</option>
                        @foreach($progressOptions as $val => $label)
                            <option value="{{ $val }}" {{ request('progress') !== null && request('progress') !== '' && request('progress') == $val ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-warning text-dark fw-bold w-100"><i class="fas fa-filter me-1"></i> Filter</button>
                    <a href="{{ route('employee.orders.index') }}" class="btn btn-outline-secondary" title="Reset Filter"><i class="fas fa-undo"></i></a>
                </div>
            </form>
        </div>
    </div>

    <!-- Orders Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Kode & Tanggal</th>
                            <th>Pelanggan</th>
                            <th>Produk & Spesifikasi</th>
                            <th>Status Pesanan</th>
                            <th>Tahap Progres</th>
                            <th>Pembayaran</th>
                            <th class="text-center pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                            @php
                                $wp = $order->workProgress();
                            @endphp
                            <tr>
                                <td class="ps-4">
                                    <span class="fw-bold text-dark">{{ $order->order_code }}</span>
                                    <br>
                                    <small class="text-muted">{{ $order->created_at->format('d M Y, H:i') }}</small>
                                </td>
                                <td>
                                    <span class="fw-bold text-dark">{{ $order->user->name ?? '-' }}</span>
                                    <br>
                                    <small class="text-muted"><i class="fas fa-phone-alt me-1"></i>{{ $order->user->phone ?? '-' }}</small>
                                </td>
                                <td>
                                    <strong>{{ $order->product_name }}</strong>
                                    <br>
                                    <small class="text-muted">
                                        Qty: {{ $order->quantity }} | Dimensi: {{ $order->dimensions ?? '-' }}
                                    </small>
                                    @if($order->material_preference)
                                        <br><small class="badge bg-light text-dark border">{{ $order->material_preference }}</small>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-{{ $order->status_badge }}">
                                        {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                    </span>
                                </td>
                                <td style="min-width: 190px;">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <small class="fw-bold" style="font-size: 0.8rem;">{{ $wp->stepName() }}</small>
                                        <small class="fw-bold text-primary">{{ $order->progress_percentage }}%</small>
                                    </div>
                                    <div class="progress" style="height: 7px;">
                                        <div class="progress-bar progress-bar-striped progress-bar-animated bg-{{ $wp->badgeClass() }}"
                                            role="progressbar"
                                            style="width: {{ $order->progress_percentage }}%;"
                                            aria-valuenow="{{ $order->progress_percentage }}"
                                            aria-valuemin="0"
                                            aria-valuemax="100">
                                        </div>
                                    </div>
                                    @if($order->progress_notes)
                                        <small class="text-muted text-truncate d-block mt-1" style="max-width: 220px;" title="{{ $order->progress_notes }}">
                                            <i class="fas fa-comment-dots me-1"></i>{{ $order->progress_notes }}
                                        </small>
                                    @endif
                                </td>
                                <td>
                                    @if($order->isFullyPaid())
                                        <span class="badge bg-success"><i class="fas fa-check-circle me-1"></i>Lunas</span>
                                    @elseif($order->isDpPaid())
                                        <span class="badge bg-info text-dark"><i class="fas fa-shield-alt me-1"></i>DP Terbayar</span>
                                    @else
                                        <span class="badge bg-secondary">Menunggu Pembayaran</span>
                                    @endif
                                </td>
                                <td class="text-center pe-4">
                                    @if($order->isDpPaid() || $order->isFullyPaid())
                                        <a href="{{ route('employee.orders.show', $order->id) }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-tasks me-1"></i> Update Progres
                                        </a>
                                    @else
                                        <a href="{{ route('employee.orders.show', $order->id) }}" class="btn btn-sm btn-outline-secondary" title="Menunggu pembayaran DP diverifikasi">
                                            <i class="fas fa-lock me-1"></i> Menunggu DP
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <i class="fas fa-inbox fa-3x mb-3 text-secondary d-block"></i>
                                    Belum ada pesanan yang sesuai dengan filter yang dipilih.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    @if($orders->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $orders->withQueryString()->links() }}
        </div>
    @endif
</div>
@endsection

@extends('layouts.employee')

@section('title', 'Dashboard Kerja Teknisi - Bengkel Las Asyraf')
@section('page-title', 'Dashboard Kerja')

@section('content')
<div class="container-fluid p-0">
    <!-- Welcome Banner -->
    <div class="card border-0 shadow-sm mb-4" style="background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);">
        <div class="card-body p-4 text-white">
            <div class="d-flex flex-wrap align-items-center justify-content-between">
                <div class="d-flex align-items-center mb-3 mb-md-0">
                    <div class="rounded-circle bg-warning text-dark d-flex align-items-center justify-content-center me-3 shadow" style="width: 54px; height: 54px; font-size: 1.5rem;">
                        <i class="fas fa-hard-hat"></i>
                    </div>
                    <div>
                        <h4 class="mb-1 text-white fw-bold">Selamat Bertugas, {{ Auth::user()->name }}!</h4>
                        <p class="mb-0 text-white-50 small">
                            <i class="fas fa-calendar-alt me-1"></i> {{ now()->isoFormat('dddd, D MMMM Y') }}
                            <span class="mx-2">•</span>
                            <i class="fas fa-tools me-1"></i> Area Produksi & Pengelasan Bengkel Las Asyraf
                        </p>
                    </div>
                </div>
                <div>
                    <a href="{{ route('employee.orders.index') }}" class="btn btn-warning text-dark fw-bold px-3 py-2 shadow-sm">
                        <i class="fas fa-clipboard-list me-1"></i> Buka Semua Antrean
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- KPI Metric Cards -->
    <div class="row g-3 mb-4">
        <!-- Siap Dikerjakan -->
        <div class="col-sm-6 col-xl-3">
            <div class="card card-kpi bg-white border-0 shadow-sm h-100">
                <div class="card-body p-3 p-md-4">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="text-muted small fw-bold text-uppercase">Siap Dikerjakan</span>
                        <div class="stat-icon-box warning">
                            <i class="fas fa-clock"></i>
                        </div>
                    </div>
                    <h2 class="mb-1 fw-bold text-dark">{{ $readyOrdersCount }}</h2>
                    <div class="small text-muted">
                        Pesanan menunggu pengerjaan awal
                    </div>
                </div>
            </div>
        </div>

        <!-- Sedang Dikerjakan -->
        <div class="col-sm-6 col-xl-3">
            <div class="card card-kpi bg-white border-0 shadow-sm h-100">
                <div class="card-body p-3 p-md-4">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="text-muted small fw-bold text-uppercase">Sedang Dikerjakan</span>
                        <div class="stat-icon-box primary">
                            <i class="fas fa-tools"></i>
                        </div>
                    </div>
                    <h2 class="mb-1 fw-bold text-primary">{{ $inProductionCount }}</h2>
                    <div class="small text-muted">
                        Aktif dalam proses pengelasan
                    </div>
                </div>
            </div>
        </div>

        <!-- Rata-rata Progres -->
        <div class="col-sm-6 col-xl-3">
            <div class="card card-kpi bg-white border-0 shadow-sm h-100">
                <div class="card-body p-3 p-md-4">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="text-muted small fw-bold text-uppercase">Rata-rata Progres</span>
                        <div class="stat-icon-box info">
                            <i class="fas fa-chart-line"></i>
                        </div>
                    </div>
                    <h2 class="mb-1 fw-bold text-dark">{{ $avgProgress }}%</h2>
                    <div class="progress" style="height: 6px;">
                        <div class="progress-bar bg-info" role="progressbar" style="width: {{ $avgProgress }}%;" aria-valuenow="{{ $avgProgress }}" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pekerjaan Selesai -->
        <div class="col-sm-6 col-xl-3">
            <div class="card card-kpi bg-white border-0 shadow-sm h-100">
                <div class="card-body p-3 p-md-4">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="text-muted small fw-bold text-uppercase">Pekerjaan Selesai</span>
                        <div class="stat-icon-box success">
                            <i class="fas fa-check-circle"></i>
                        </div>
                    </div>
                    <h2 class="mb-1 fw-bold text-success">{{ $completedCount }}</h2>
                    <div class="small text-muted">
                        Total pesanan selesai diproduksi
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Grid: Active Tasks & Workshop Guidelines -->
    <div class="row g-4">
        <!-- Left: Active Tasks -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom py-3 d-flex align-items-center justify-content-between">
                    <h6 class="mb-0 fw-bold text-dark">
                        <i class="fas fa-tasks text-warning me-2"></i>Tugas & Pengerjaan Berjalan
                    </h6>
                    <a href="{{ route('employee.orders.index') }}" class="btn btn-sm btn-outline-secondary">
                        Lihat Semua ({{ $totalActiveCount }})
                    </a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">Pesanan</th>
                                    <th>Produk & Spek</th>
                                    <th>Progres Pengerjaan</th>
                                    <th>Status Pembayaran</th>
                                    <th class="text-center pe-4">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($activeTasks as $task)
                                    @php
                                        $wp = $task->workProgress();
                                    @endphp
                                    <tr>
                                        <td class="ps-4">
                                            <span class="fw-bold text-dark">{{ $task->order_code }}</span>
                                            <br>
                                            <small class="text-muted">
                                                <i class="fas fa-user me-1"></i>{{ $task->user->name ?? '-' }}
                                            </small>
                                        </td>
                                        <td>
                                            <strong>{{ $task->product_name }}</strong>
                                            <br>
                                            <small class="text-muted">
                                                Qty: {{ $task->quantity }} | Dimensi: {{ $task->dimensions ?? '-' }}
                                            </small>
                                        </td>
                                        <td style="min-width: 170px;">
                                            <div class="d-flex justify-content-between align-items-center mb-1">
                                                <small class="fw-bold" style="font-size: 0.8rem;">{{ $wp->stepName() }}</small>
                                                <small class="fw-bold text-primary">{{ $task->progress_percentage }}%</small>
                                            </div>
                                            <div class="progress" style="height: 6px;">
                                                <div class="progress-bar bg-{{ $wp->badgeClass() }}" role="progressbar" style="width: {{ $task->progress_percentage }}%;" aria-valuenow="{{ $task->progress_percentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </td>
                                        <td>
                                            @if($task->isFullyPaid())
                                                <span class="badge bg-success"><i class="fas fa-check-circle me-1"></i>Lunas</span>
                                            @elseif($task->isDpPaid())
                                                <span class="badge bg-info text-dark"><i class="fas fa-shield-alt me-1"></i>DP Terbayar</span>
                                            @else
                                                <span class="badge bg-secondary">Menunggu Pembayaran</span>
                                            @endif
                                        </td>
                                        <td class="text-center pe-4">
                                            @if($task->isDpPaid() || $task->isFullyPaid())
                                                <a href="{{ route('employee.orders.show', $task->id) }}" class="btn btn-sm btn-primary">
                                                    <i class="fas fa-edit me-1"></i> Update
                                                </a>
                                            @else
                                                <a href="{{ route('employee.orders.show', $task->id) }}" class="btn btn-sm btn-outline-secondary" title="Menunggu DP diverifikasi">
                                                    <i class="fas fa-lock me-1"></i> Menunggu DP
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-5 text-muted">
                                            <i class="fas fa-clipboard-check fa-3x mb-3 text-secondary d-block"></i>
                                            Tidak ada tugas pengerjaan aktif saat ini.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Shortcuts, SOP & Recent Finished -->
        <div class="col-lg-4">
            <!-- SOP & Safety Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom py-3">
                    <h6 class="mb-0 fw-bold text-dark">
                        <i class="fas fa-shield-alt text-danger me-2"></i>Panduan K3 & Keselamatan Kerja
                    </h6>
                </div>
                <div class="card-body p-3">
                    <ul class="list-unstyled mb-0">
                        <li class="d-flex align-items-start mb-3">
                            <span class="badge bg-warning text-dark rounded-circle p-2 me-2">1</span>
                            <small class="text-muted">Gunakan APD lengkap: Topeng las/helm pelindung, sarung tangan kulit las, & sepatu safety.</small>
                        </li>
                        <li class="d-flex align-items-start mb-3">
                            <span class="badge bg-warning text-dark rounded-circle p-2 me-2">2</span>
                            <small class="text-muted">Periksa sambungan kabel mesin las dan grounding sebelum menyalakan arus pengelasan.</small>
                        </li>
                        <li class="d-flex align-items-start mb-3">
                            <span class="badge bg-warning text-dark rounded-circle p-2 me-2">3</span>
                            <small class="text-muted">Pastikan area kerja bebas dari bahan yang mudah terbakar (thinner, cat, bensin).</small>
                        </li>
                        <li class="d-flex align-items-start">
                            <span class="badge bg-warning text-dark rounded-circle p-2 me-2">4</span>
                            <small class="text-muted">Perbarui catatan progres lapangan tiap tahapan selesai untuk kemudahan pelacakan pelanggan.</small>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Recently Completed -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom py-3">
                    <h6 class="mb-0 fw-bold text-dark">
                        <i class="fas fa-history text-success me-2"></i>Selesai Baru-baru Ini
                    </h6>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @forelse($recentlyCompleted as $completed)
                            <li class="list-group-item px-3 py-2 d-flex align-items-center justify-content-between">
                                <div>
                                    <span class="fw-bold text-dark small">{{ $completed->order_code }}</span>
                                    <div class="text-muted" style="font-size: 0.75rem;">{{ $completed->product_name }}</div>
                                </div>
                                <span class="badge bg-success-subtle text-success border border-success" style="font-size: 0.7rem;">
                                    <i class="fas fa-check me-1"></i> 100% Selesai
                                </span>
                            </li>
                        @empty
                            <li class="list-group-item text-center text-muted py-3 small">
                                Belum ada riwayat pengerjaan selesai.
                            </li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

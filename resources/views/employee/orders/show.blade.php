@extends('layouts.employee')

@section('title', 'Detail Pekerjaan ' . $order->order_code . ' - Panel Karyawan')
@section('page-title', 'Pengerjaan: ' . $order->order_code)

@section('content')
<div class="container-fluid p-0">
    <!-- Breadcrumb Bar -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1">
                    <li class="breadcrumb-item"><a href="{{ route('employee.dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('employee.orders.index') }}" class="text-decoration-none">Antrean</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $order->order_code }}</li>
                </ol>
            </nav>
            <h5 class="fw-bold text-dark mb-0">Rincian Teknis & Progres Produksi</h5>
        </div>
        <div class="mt-2 mt-md-0">
            <a href="{{ route('employee.orders.index') }}" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> Kembali ke Antrean
            </a>
        </div>
    </div>

    <div class="row g-4">
        <!-- Left: Order Details & Design Blueprint -->
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center py-3">
                    <h6 class="mb-0 fw-bold"><i class="fas fa-tools text-warning me-2"></i>Spesifikasi Pengerjaan Bengkel</h6>
                    <span class="badge bg-{{ $order->status_badge }} px-2 py-1">
                        {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                    </span>
                </div>
                <div class="card-body p-4">
                    <table class="table table-borderless align-middle mb-0">
                        <tr>
                            <th width="35%" class="text-muted">Kode Pesanan</th>
                            <td><span class="fw-bold text-dark fs-5">{{ $order->order_code }}</span></td>
                        </tr>
                        <tr>
                            <th class="text-muted">Data Pelanggan</th>
                            <td>
                                <strong class="text-dark">{{ $order->user->name ?? '-' }}</strong><br>
                                <small class="text-muted"><i class="fas fa-phone-alt me-1"></i>{{ $order->user->phone ?? '-' }}</small><br>
                                <small class="text-muted"><i class="fas fa-map-marker-alt me-1"></i>{{ $order->user->address ?? 'Talaga, Bone' }}</small>
                            </td>
                        </tr>
                        <tr>
                            <th class="text-muted">Nama Produk</th>
                            <td><strong class="text-dark">{{ $order->product_name }}</strong></td>
                        </tr>
                        <tr>
                            <th class="text-muted">Dimensi / Ukuran</th>
                            <td><span class="badge bg-light text-dark border">{{ $order->dimensions ?? '-' }}</span></td>
                        </tr>
                        <tr>
                            <th class="text-muted">Preferensi Material</th>
                            <td><span class="badge bg-light text-dark border">{{ $order->material_preference ?? 'Standar Bengkel' }}</span></td>
                        </tr>
                        <tr>
                            <th class="text-muted">Jumlah Unit</th>
                            <td><strong class="text-dark">{{ $order->quantity }} unit</strong></td>
                        </tr>
                        <tr>
                            <th class="text-muted">Deskripsi / Request</th>
                            <td class="text-dark">{{ $order->description }}</td>
                        </tr>
                        <tr>
                            <th class="text-muted">Status Pembayaran</th>
                            <td>
                                @if($order->isFullyPaid())
                                    <span class="badge bg-success"><i class="fas fa-check-circle me-1"></i>Lunas (100%)</span>
                                @elseif($order->isDpPaid())
                                    <span class="badge bg-info text-dark"><i class="fas fa-shield-alt me-1"></i>DP Terbayar (Siap Dikerjakan)</span>
                                @else
                                    <span class="badge bg-warning text-dark">Belum Lunas DP</span>
                                @endif
                            </td>
                        </tr>
                    </table>

                    @if($order->reference_design_path)
                        <hr class="my-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="fw-bold text-dark mb-0"><i class="fas fa-drafting-compass text-warning me-2"></i>Gambar Referensi / Blueprint Desain:</h6>
                            <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#blueprintModal">
                                <i class="fas fa-expand me-1"></i> Perbesar
                            </button>
                        </div>
                        <div class="text-center bg-light p-3 rounded border">
                            <a href="#" data-bs-toggle="modal" data-bs-target="#blueprintModal">
                                <img src="{{ asset('storage/' . $order->reference_design_path) }}"
                                     alt="Desain Referensi"
                                     class="img-fluid rounded shadow-sm"
                                     style="max-height: 320px; object-fit: contain;">
                            </a>
                        </div>

                        <!-- Blueprint Modal -->
                        <div class="modal fade" id="blueprintModal" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-xl modal-dialog-centered">
                                <div class="modal-content border-0 shadow-lg">
                                    <div class="modal-header bg-dark text-white">
                                        <h6 class="modal-title fw-bold">Blueprint Desain - {{ $order->order_code }}</h6>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body text-center p-3 bg-light">
                                        <img src="{{ asset('storage/' . $order->reference_design_path) }}" class="img-fluid rounded shadow" alt="Desain Blueprint">
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right: Update Progress Form & Status Timeline -->
        <div class="col-lg-5">
            <!-- Progress Update Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-warning text-dark py-3">
                    <h6 class="mb-0 fw-bold"><i class="fas fa-sliders-h me-2"></i>Pembaruan Progres Kerja</h6>
                </div>
                <div class="card-body p-4">
                    @php
                        $currentWp = $order->workProgress();
                    @endphp

                    <!-- Current Status Banner -->
                    <div class="bg-light p-3 rounded mb-4 text-center border">
                        <span class="text-muted d-block small text-uppercase fw-bold">Tahap Pengerjaan Saat Ini</span>
                        <h4 class="text-dark fw-bold mt-1 mb-2">{{ $currentWp->label() }}</h4>
                        <div class="progress" style="height: 10px;">
                            <div class="progress-bar progress-bar-striped progress-bar-animated bg-{{ $currentWp->badgeClass() }}"
                                role="progressbar"
                                style="width: {{ $order->progress_percentage }}%;"
                                aria-valuenow="{{ $order->progress_percentage }}"
                                aria-valuemin="0"
                                aria-valuemax="100">
                            </div>
                        </div>
                    </div>

                    @if(!$order->isDpPaid() && !$order->isFullyPaid())
                        <div class="alert alert-warning border-0 shadow-sm mb-3">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-lock fa-2x text-warning me-3"></i>
                                <div>
                                    <h6 class="fw-bold text-dark mb-1">Pengerjaan Belum Dapat Dimulai</h6>
                                    <p class="small text-muted mb-0">Pesanan ini masih menunggu pembayaran Down Payment (DP) dari pelanggan atau pembayaran belum diverifikasi admin. Form pengerjaan dikunci demi keamanan operasional bengkel.</p>
                                </div>
                            </div>
                        </div>
                    @else
                        <!-- Progress Update Form -->
                        <form method="POST" action="{{ route('employee.orders.updateProgress', $order->id) }}" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label for="progress_percentage" class="form-label fw-bold small text-dark">
                                    Pilih Tahap & Persentase Selesai <span class="text-danger">*</span>
                                </label>
                                <select class="form-select @error('progress_percentage') is-invalid @enderror"
                                        id="progress_percentage" name="progress_percentage" required>
                                    @foreach($progressOptions as $case)
                                        <option value="{{ $case->value }}" {{ (int) old('progress_percentage', $order->progress_percentage) === $case->value ? 'selected' : '' }}>
                                            {{ $case->label() }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('progress_percentage')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="progress_notes" class="form-label fw-bold small text-dark">Catatan Pengerjaan / Kendala Lapangan</label>
                                <textarea class="form-control @error('progress_notes') is-invalid @enderror"
                                    id="progress_notes" name="progress_notes" rows="4"
                                    placeholder="Contoh: Pemotongan besi hollow selesai, lanjut pengelasan siku...">{{ old('progress_notes', $order->progress_notes) }}</textarea>
                                @error('progress_notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted d-block mt-1">Catatan ini otomatis tampil di pelacakan pesanan pelanggan.</small>
                            </div>

                            <div class="mb-4">
                                <label for="progress_photo" class="form-label fw-bold small text-dark">
                                    <i class="fas fa-camera me-1"></i> Foto Dokumentasi Pengerjaan (Opsional)
                                </label>
                                @if($order->progress_photo_path)
                                    <div class="d-flex align-items-center mb-2 p-2 bg-white rounded border">
                                        <img src="{{ asset('storage/' . $order->progress_photo_path) }}" alt="Foto Progres" class="rounded me-2 border" style="width: 52px; height: 52px; object-fit: cover;">
                                        <div class="small flex-grow-1">
                                            <span class="badge bg-success mb-1"><i class="fas fa-check me-1"></i>Foto Tersimpan</span>
                                            <div class="text-muted" style="font-size: 0.75rem;">Upload file baru di bawah jika ingin memperbarui foto.</div>
                                        </div>
                                        <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#progressPhotoModal">
                                            <i class="fas fa-expand me-1"></i>Lihat
                                        </button>
                                    </div>
                                @endif
                                <input type="file" class="form-control @error('progress_photo') is-invalid @enderror"
                                       id="progress_photo" name="progress_photo" accept="image/*">
                                @error('progress_photo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted d-block mt-1">Lampirkan foto fisik pengerjaan di bengkel untuk meyakinkan pelanggan. Format: JPG, PNG, WEBP (Maks 3MB).</small>
                            </div>

                            <button type="submit" class="btn btn-warning text-dark fw-bold w-100 py-2 shadow-sm">
                                <i class="fas fa-save me-2"></i>Simpan Progres Pengerjaan
                            </button>
                        </form>

                        @if($order->progress_photo_path)
                            <!-- Progress Photo Modal -->
                            <div class="modal fade" id="progressPhotoModal" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-lg modal-dialog-centered">
                                    <div class="modal-content border-0 shadow-lg">
                                        <div class="modal-header bg-dark text-white">
                                            <h6 class="modal-title fw-bold"><i class="fas fa-camera text-warning me-2"></i>Foto Dokumentasi Pengerjaan Terkini</h6>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body text-center p-3 bg-light">
                                            <img src="{{ asset('storage/' . $order->progress_photo_path) }}" class="img-fluid rounded shadow" alt="Foto Dokumentasi Pengerjaan">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endif
                </div>
            </div>

            <!-- Back Navigation -->
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center p-3">
                    <a href="{{ route('employee.orders.index') }}" class="btn btn-outline-secondary w-100">
                        <i class="fas fa-arrow-left me-2"></i>Kembali ke Antrean Pengerjaan
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

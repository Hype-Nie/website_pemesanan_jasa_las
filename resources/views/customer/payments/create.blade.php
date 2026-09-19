@extends('layouts.app')

@section('title', 'Pembayaran ' . ($targetPaymentType === 'down_payment' ? 'DP' : 'Pelunasan') . ' - Bengkel Asyraf')

@section('content')
    <!-- Page Header -->
    <x-page-header title="Instruksi & Konfirmasi Pembayaran" :breadcrumbs="[
        'Pesanan Saya' => route('customer.orders.index'),
        $order->order_code => route('customer.orders.show', $order->id),
        'Pembayaran' => null
    ]" />

    <!-- Payment Container Start -->
    <div class="container-fluid py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-9 col-xl-8">

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @php
                        $isDp = ($targetPaymentType ?? 'down_payment') === 'down_payment';
                    @endphp

                    <!-- 1. Payment Stage & Step-by-step Guidance Card -->
                    <div class="card border-0 shadow-sm mb-4 overflow-hidden">
                        <div class="card-header bg-primary text-white py-3 px-4 d-flex flex-wrap justify-content-between align-items-center gap-2">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-file-invoice-dollar text-warning fs-5 me-2"></i>
                                <h5 class="mb-0 text-white fw-bold">Panduan Alur Pembayaran Bengkel</h5>
                            </div>
                            <span class="badge bg-warning text-dark px-3 py-2 fw-bold text-uppercase" style="letter-spacing: 0.5px;">
                                <i class="fas {{ $isDp ? 'fa-shield-alt' : 'fa-check-double' }} me-1"></i>
                                {{ $isDp ? 'Tahap 1: Uang Muka (DP)' : 'Tahap 2: Pelunasan' }}
                            </span>
                        </div>

                        <div class="card-body p-4 bg-white">
                            <!-- Visual Two-Step Progress Journey -->
                            <div class="row g-3 mb-4 text-center">
                                <div class="col-6">
                                    <div class="p-3 rounded border {{ $isDp ? 'border-warning bg-light shadow-sm' : 'border-success bg-light' }}" style="position: relative;">
                                        @if(!$isDp)
                                            <span class="position-absolute top-0 start-50 translate-middle badge rounded-pill bg-success px-2 py-1">
                                                <i class="fas fa-check me-1"></i>Selesai
                                            </span>
                                        @else
                                            <span class="position-absolute top-0 start-50 translate-middle badge rounded-pill bg-warning text-dark px-2 py-1 fw-bold">
                                                Tahap Aktif
                                            </span>
                                        @endif
                                        <div class="fw-bold text-dark mt-1">1. Down Payment (DP 50%)</div>
                                        <small class="{{ $isDp ? 'text-dark fw-semibold' : 'text-success' }}">
                                            {{ $isDp ? 'Wajib sebelum pengerjaan dimulai' : 'Terverifikasi & Diterima' }}
                                        </small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="p-3 rounded border {{ !$isDp ? 'border-warning bg-light shadow-sm' : 'border-secondary border-opacity-25 bg-light' }}" style="position: relative;">
                                        @if(!$isDp)
                                            <span class="position-absolute top-0 start-50 translate-middle badge rounded-pill bg-warning text-dark px-2 py-1 fw-bold">
                                                Tahap Aktif
                                            </span>
                                        @else
                                            <span class="position-absolute top-0 start-50 translate-middle badge rounded-pill bg-secondary text-white px-2 py-1">
                                                Tahap Berikutnya
                                            </span>
                                        @endif
                                        <div class="fw-bold text-dark mt-1">2. Pelunasan (50%)</div>
                                        <small class="{{ !$isDp ? 'text-dark fw-semibold' : 'text-muted' }}">
                                            {{ !$isDp ? 'Wajib sebelum serah terima/selesai' : 'Dibayar saat pengerjaan berlangsung' }}
                                        </small>
                                    </div>
                                </div>
                            </div>

                            <!-- Clear Guidance Text (Ultra Readable) -->
                            <div class="border-top pt-3">
                                @if($isDp)
                                    <h6 class="fw-bold text-dark mb-2">
                                        <i class="fas fa-info-circle text-warning me-1"></i> Ketentuan Pembayaran Uang Muka (DP 50%):
                                    </h6>
                                    <p class="text-dark mb-2" style="font-size: 0.95rem; line-height: 1.6;">
                                        Bengkel Asyraf menerapkan sistem DP 50% untuk mengonfirmasi pesanan secara resmi, pengadaan material besi/baja, serta mengalokasikan teknisi bengkel. Pengerjaan fisik (pemotongan, perakitan, dan pengelasan) akan langsung dimulai setelah bukti transfer DP Anda diverifikasi admin.
                                    </p>
                                    <div class="p-3 rounded border" style="background-color: #F8FAFC;">
                                        <small class="text-dark d-block">
                                            <strong>Catatan:</strong> Sisa pembayaran 50% (Pelunasan) dapat dibayarkan ketika teknisi sedang menyelesaikan pesanan atau sebelum pesanan diambil/dikirim.
                                        </small>
                                    </div>
                                @else
                                    <h6 class="fw-bold text-dark mb-2">
                                        <i class="fas fa-receipt text-primary me-1"></i> Ketentuan Pelunasan Sisa Pembayaran (50%):
                                    </h6>
                                    <p class="text-dark mb-2" style="font-size: 0.95rem; line-height: 1.6;">
                                        DP Anda telah terverifikasi dan pesanan sedang atau telah dikerjakan oleh teknisi bengkel. Silakan lakukan pembayaran pelunasan sebesar tagihan di bawah ini untuk merampungkan pesanan Anda.
                                    </p>

                                    <!-- Highlight Notice Box for Order Completion Rule -->
                                    <div class="p-3 rounded border border-warning mt-3" style="background-color: #FFFBEB;">
                                        <div class="d-flex align-items-start">
                                            <div class="rounded-circle bg-warning text-dark p-2 me-3 flex-shrink-0 d-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">
                                                <i class="fas fa-exclamation-triangle"></i>
                                            </div>
                                            <div>
                                                <h6 class="fw-bold text-dark mb-1">Penting: Syarat Perubahan Status "Selesai"</h6>
                                                <p class="text-dark mb-0 small" style="line-height: 1.6;">
                                                    Admin bengkel <strong>hanya dapat mengubah status pesanan menjadi "Selesai"</strong> dan menyerahkan atau mengirimkan produk ke alamat Anda setelah pembayaran pelunasan ini berhasil diverifikasi.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- 2. Combined Order Summary & Official Bank Account Card -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-primary text-white py-3 px-4">
                            <h5 class="mb-0 text-white fw-bold"><i class="fas fa-clipboard-list text-warning me-2"></i>Rincian Tagihan & Rekening Pembayaran</h5>
                        </div>
                        <div class="card-body p-4 bg-white">
                            <div class="row g-4">
                                <!-- Order Billing Details -->
                                <div class="col-md-6 border-end-md">
                                    <h6 class="text-uppercase fw-bold text-muted small mb-3">Informasi Pesanan</h6>
                                    <div class="mb-2">
                                        <span class="text-muted small d-block">Kode Pesanan:</span>
                                        <span class="fw-bold text-primary fs-5">{{ $order->order_code }}</span>
                                    </div>
                                    <div class="mb-3">
                                        <span class="text-muted small d-block">Produk:</span>
                                        <strong class="text-dark">{{ $order->product_name }}</strong>
                                        <span class="badge bg-light text-dark border ms-1">{{ $order->quantity }} unit</span>
                                    </div>

                                    <div class="table-responsive">
                                        <table class="table table-sm table-borderless mb-0">
                                            <tr>
                                                <td class="text-muted">Total Harga Kesepakatan:</td>
                                                <td class="text-end fw-bold text-dark">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                                            </tr>
                                            <tr>
                                                <td class="text-muted">Ketentuan Uang Muka (DP):</td>
                                                <td class="text-end fw-bold text-dark">Rp {{ number_format($order->requiredDpAmount(), 0, ',', '.') }}</td>
                                            </tr>
                                            <tr>
                                                <td class="text-muted">Sudah Terbayar (Verifikasi):</td>
                                                <td class="text-end fw-bold text-success">Rp {{ number_format($order->totalPaid(), 0, ',', '.') }}</td>
                                            </tr>
                                        </table>
                                    </div>

                                    <!-- Highlighted Amount to Pay -->
                                    <div class="p-3 rounded mt-3 border border-2 border-warning" style="background-color: #FFFDF5;">
                                        <span class="text-dark small fw-bold text-uppercase d-block">
                                            {{ $isDp ? 'Tagihan DP yang Harus Ditransfer Sekarang:' : 'Sisa Tagihan Pelunasan Sekarang:' }}
                                        </span>
                                        <span class="fs-4 fw-bold text-dark d-block mt-1">
                                            Rp {{ number_format($expectedAmount, 0, ',', '.') }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Official Bank Transfer Details -->
                                <div class="col-md-6">
                                    <h6 class="text-uppercase fw-bold text-muted small mb-3">Rekening Resmi Bengkel Asyraf</h6>
                                    <div class="p-3 rounded border bg-light mb-3">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <span class="badge bg-primary text-white px-2 py-1">BANK BCA</span>
                                            <span class="badge bg-warning text-dark px-2 py-1 fw-bold">BANK BRI</span>
                                        </div>
                                        <div class="mb-2">
                                            <span class="text-muted small d-block">Nomor Rekening:</span>
                                            <span class="fs-4 fw-bold text-dark user-select-all font-monospace" id="accountNumberText">1234567890</span>
                                        </div>
                                        <div class="mb-0">
                                            <span class="text-muted small d-block">Atas Nama:</span>
                                            <strong class="text-dark">Bengkel Asyraf (Talaga, Bone)</strong>
                                        </div>
                                    </div>

                                    <div class="p-3 rounded border" style="background-color: #F8FAFC;">
                                        <div class="d-flex">
                                            <i class="fas fa-shield-alt text-primary fs-5 me-2 mt-1"></i>
                                            <small class="text-dark" style="line-height: 1.5;">
                                                Transfer tepat sebesar <strong>Rp {{ number_format($expectedAmount, 0, ',', '.') }}</strong> agar admin dapat memverifikasi pembayaran Anda lebih cepat.
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 3. Payment Upload Form -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-primary text-white py-3 px-4">
                            <h5 class="mb-0 text-white fw-bold">
                                <i class="fas fa-upload text-warning me-2"></i>Formulir Unggah Bukti Pembayaran
                            </h5>
                        </div>
                        <div class="card-body p-4 bg-white">
                            <form method="POST" action="{{ route('customer.payments.store', $order->id) }}" enctype="multipart/form-data">
                                @csrf

                                <input type="hidden" name="payment_type" value="{{ $targetPaymentType }}">

                                <div class="mb-3">
                                    <label class="form-label fw-bold text-dark">Tahap Pembayaran</label>
                                    <input type="text" class="form-control bg-light text-dark fw-semibold" readonly
                                        value="{{ $isDp ? 'Tahap 1: Uang Muka (Down Payment 50%)' : 'Tahap 2: Pelunasan Sisa Pembayaran (50%)' }}">
                                </div>

                                <div class="mb-3">
                                    <label for="amount" class="form-label fw-bold text-dark">
                                        Nominal Transfer (Rp) <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light text-dark fw-bold">Rp</span>
                                        <input type="number" class="form-control @error('amount') is-invalid @enderror"
                                            id="amount" name="amount"
                                            value="{{ old('amount', $expectedAmount) }}"
                                            min="1" required>
                                    </div>
                                    <small class="text-muted mt-1 d-block">Nominal yang ditransfer sesuai bukti struk/mutasi.</small>
                                    @error('amount')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="row g-3 mb-3">
                                    <div class="col-md-6">
                                        <label for="bank_name" class="form-label fw-bold text-dark">Nama Bank Pengirim <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('bank_name') is-invalid @enderror"
                                            id="bank_name" name="bank_name" value="{{ old('bank_name') }}"
                                            placeholder="Contoh: BCA, BRI, Mandiri, BNI" required>
                                        @error('bank_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="account_name" class="form-label fw-bold text-dark">Nama Pemilik Rekening Pengirim <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('account_name') is-invalid @enderror"
                                            id="account_name" name="account_name" value="{{ old('account_name') }}"
                                            placeholder="Nama sesuai buku rekening/aplikasi" required>
                                        @error('account_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label for="proof_image" class="form-label fw-bold text-dark">
                                        Foto / Screenshot Struk Transfer <span class="text-danger">*</span>
                                    </label>
                                    <input type="file" class="form-control @error('proof_image') is-invalid @enderror"
                                        id="proof_image" name="proof_image" accept="image/*" required>
                                    <small class="text-muted mt-1 d-block">
                                        Format: JPG, JPEG, PNG, WEBP (Ukuran file maksimal 3MB).
                                    </small>
                                    @error('proof_image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <button type="submit" class="btn btn-warning text-dark fw-bold w-100 py-3 shadow-sm fs-5">
                                    <i class="fas fa-paper-plane me-2"></i>Kirim Bukti Pembayaran {{ $isDp ? 'DP' : 'Pelunasan' }}
                                </button>

                                <div class="text-center mt-3">
                                    <small class="text-muted">
                                        <i class="fas fa-lock me-1"></i>Verifikasi diproses langsung oleh Admin Bengkel Asyraf.
                                    </small>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Navigation Link Back -->
                    <div class="text-center mb-5">
                        <a href="{{ route('customer.orders.show', $order->id) }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Kembali ke Rincian Pesanan
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <!-- Payment Container End -->
@endsection

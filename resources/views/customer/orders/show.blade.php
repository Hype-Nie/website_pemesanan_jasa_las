@extends('layouts.app')

@section('title', 'Detail Pesanan ' . $order->order_code . ' - Bengkel Asyraf')

@section('content')
    <!-- Page Header -->
    <x-page-header title="Detail Pesanan" :breadcrumbs="['Pesanan Saya' => route('customer.orders.index'), $order->order_code => null]" />

    <!-- Order Detail Start -->
    <div class="container-fluid py-5">
        <div class="container">
            <div class="row g-4">
                <!-- Order Info -->
                <div class="col-lg-8 wow fadeInUp" data-wow-delay="0.1s">
                    <div class="card mb-4">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0"><i class="fas fa-clipboard-list me-2"></i>Informasi Pesanan</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="35%">Kode Pesanan</th>
                                    <td><span class="fw-bold text-primary">{{ $order->order_code }}</span></td>
                                </tr>
                                <tr>
                                    <th>Tipe Pesanan</th>
                                    <td>
                                        @if($order->catalog_product_id && $order->catalogProduct)
                                            <span class="badge bg-info text-dark"><i class="fas fa-book-open me-1"></i>Produk Katalog</span>
                                            <a href="{{ route('catalog.show', $order->catalog_product_id) }}" class="small ms-2 text-decoration-none">
                                                <i class="fas fa-external-link-alt me-1"></i>Lihat Produk di Katalog
                                            </a>
                                        @else
                                            <span class="badge bg-secondary"><i class="fas fa-magic me-1"></i>Pesanan Custom</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Nama Produk</th>
                                    <td>
                                        <span class="fw-bold text-dark">{{ $order->product_name }}</span>
                                        @if($order->catalog_product_id && $order->catalogProduct)
                                            <span class="badge bg-light text-muted border ms-2">Kategori: {{ $order->catalogProduct->category->name ?? 'Katalog' }}</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Deskripsi</th>
                                    <td>{{ $order->description }}</td>
                                </tr>
                                <tr>
                                    <th>Ukuran</th>
                                    <td>{{ $order->dimensions ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Material</th>
                                    <td>{{ $order->material_preference ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Jumlah</th>
                                    <td>{{ $order->quantity }} unit</td>
                                </tr>
                                @if($order->catalog_product_id && $order->catalogProduct)
                                    <tr>
                                        <th>Harga Satuan</th>
                                        <td>Rp {{ number_format($order->catalogProduct->price_estimate, 0, ',', '.') }}</td>
                                    </tr>
                                @endif
                                <tr>
                                    <th>Total Harga</th>
                                    <td>
                                        @if($order->total_price)
                                            <span class="fs-5 fw-bold text-primary">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                                            @if($order->catalog_product_id && $order->catalogProduct)
                                                <small class="text-success ms-2"><i class="fas fa-check-circle me-1"></i>Harga Pasti Katalog ({{ $order->quantity }} × Rp {{ number_format($order->catalogProduct->price_estimate, 0, ',', '.') }})</small>
                                            @endif
                                        @else
                                            <span class="text-muted"><i class="fas fa-hourglass-half me-1"></i>Menunggu konfirmasi dan penetapan harga dari admin</span>
                                        @endif
                                    </td>
                                </tr>
                                @if($order->total_price)
                                    <tr>
                                        <th>Ketentuan DP</th>
                                        <td>
                                            <span class="fw-bold text-warning">Rp {{ number_format($order->requiredDpAmount(), 0, ',', '.') }}</span>
                                            <small class="text-muted">({{ $order->isDpPaid() ? 'Sudah Dibayar' : 'Wajib Sebelum Pengerjaan' }})</small>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Total Terbayar</th>
                                        <td>
                                            <span class="fw-bold text-success">Rp {{ number_format($order->totalPaid(), 0, ',', '.') }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Sisa Tagihan</th>
                                        <td>
                                            <span class="fw-bold {{ $order->remainingBalance() > 0 ? 'text-danger' : 'text-success' }}">
                                                Rp {{ number_format($order->remainingBalance(), 0, ',', '.') }}
                                            </span>
                                        </td>
                                    </tr>
                                @endif
                                <tr>
                                    <th>Tanggal Pesanan</th>
                                    <td>{{ $order->created_at->format('d F Y, H:i') }} WIB</td>
                                </tr>
                            </table>

                            @if($order->reference_design_path)
                                <div class="mt-3">
                                    <h6 class="fw-bold">Referensi Desain:</h6>
                                    <a href="#" data-bs-toggle="modal" data-bs-target="#designModal" title="Klik untuk memperbesar">
                                        <img class="img-fluid rounded border" src="{{ asset('storage/' . $order->reference_design_path) }}" alt="Referensi Desain" style="max-height: 300px; transition: 0.3s;" onmouseover="this.style.opacity=0.8" onmouseout="this.style.opacity=1">
                                    </a>

                                    <!-- Modal Referensi Desain -->
                                    <div class="modal fade" id="designModal" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-lg modal-dialog-centered">
                                            <div class="modal-content border-0 shadow-lg">
                                                <div class="modal-header border-0 pb-0">
                                                    <h5 class="modal-title text-muted fw-bold fs-6">Referensi Desain</h5>
                                                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body text-center pt-2 pb-4">
                                                    <img src="{{ asset('storage/' . $order->reference_design_path) }}" class="img-fluid rounded" alt="Referensi Desain" style="max-height: 80vh;">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if($order->admin_notes)
                                <div class="alert alert-info mt-3">
                                    <h6 class="fw-bold"><i class="fas fa-comment-alt me-2"></i>Catatan Admin:</h6>
                                    <p class="mb-0">{{ $order->admin_notes }}</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Payment Section -->
                    <div class="card">
                        <div class="card-header bg-dark text-white">
                            <h5 class="mb-0"><i class="fas fa-credit-card me-2"></i>Riwayat Pembayaran</h5>
                        </div>
                        <div class="card-body">
                            @forelse($order->payments ?? [] as $payment)
                                <div class="border-bottom py-3">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <strong>Rp {{ number_format($payment->amount, 0, ',', '.') }}</strong>
                                            <span class="badge bg-{{ $payment->typeBadgeClass() }} ms-1">
                                                {{ $payment->typeLabel() }}
                                            </span>
                                            <br>
                                            <small class="text-muted">{{ $payment->bank_name }} - {{ $payment->account_name }}</small>
                                            <br>
                                            <small class="text-muted">{{ $payment->created_at->format('d M Y, H:i') }}</small>
                                            
                                            @if($payment->proof_image_path)
                                                <div class="mt-2">
                                                    <button type="button" class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#imageModal{{ $payment->id }}">
                                                        <i class="fas fa-image me-1"></i>Lihat Bukti
                                                    </button>

                                                    <!-- Modal Bukti Pembayaran -->
                                                    <div class="modal fade" id="imageModal{{ $payment->id }}" tabindex="-1" aria-hidden="true">
                                                        <div class="modal-dialog modal-lg modal-dialog-centered">
                                                            <div class="modal-content border-0 shadow-lg">
                                                                <div class="modal-header border-0 pb-0">
                                                                    <h5 class="modal-title text-muted fw-bold fs-6">Bukti Pembayaran</h5>
                                                                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body text-center pt-2 pb-4">
                                                                    <img src="{{ asset('storage/' . $payment->proof_image_path) }}" class="img-fluid rounded" alt="Bukti Pembayaran" style="max-height: 80vh;">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="text-end">
                                            @php
                                                $paymentStatusColors = [
                                                    'pending' => 'warning',
                                                    'verified' => 'success',
                                                    'rejected' => 'danger',
                                                ];
                                                $paymentStatusLabels = [
                                                    'pending' => 'Menunggu Verifikasi',
                                                    'verified' => 'Terverifikasi',
                                                    'rejected' => 'Ditolak',
                                                ];
                                            @endphp
                                            <span class="badge bg-{{ $paymentStatusColors[$payment->status] ?? 'secondary' }}">
                                                {{ $paymentStatusLabels[$payment->status] ?? $payment->status }}
                                            </span>
                                        </div>
                                    </div>
                                    
                                    @if($payment->status === 'rejected' && $payment->admin_notes)
                                        <div class="alert alert-danger mt-3 mb-0 py-2 px-3">
                                            <strong><i class="fas fa-exclamation-circle me-1"></i>Alasan Penolakan:</strong> {{ $payment->admin_notes }}
                                        </div>
                                    @endif
                                </div>
                            @empty
                                <p class="text-muted text-center py-3 mb-0">Belum ada pembayaran.</p>
                            @endforelse

                            {{-- Smart Payment Action Trigger & Guidance --}}
                            @if($order->total_price && $order->status !== 'cancelled')
                                <div class="mt-4 pt-3 border-top">
                                    @if(!$order->isDpPaid())
                                        <div class="card border-0 mb-3 text-start rounded-3 shadow-sm" style="background-color: #FFFBEB; border-left: 5px solid #F59E0B !important;">
                                            <div class="card-body p-3">
                                                <div class="d-flex align-items-start">
                                                    <div class="rounded-circle bg-warning text-dark p-2 me-3 flex-shrink-0 d-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">
                                                        <i class="fas fa-shield-alt"></i>
                                                    </div>
                                                    <div>
                                                        <h6 class="fw-bold text-dark mb-1">Panduan Pembayaran Uang Muka (DP 50%)</h6>
                                                        <p class="mb-1 text-dark small" style="line-height: 1.5;">
                                                            Pesanan Anda telah dikonfirmasi admin. Sesuai prosedur resmi Bengkel Asyraf, teknisi baru dapat memulai pengerjaan fisik setelah pembayaran DP 50% diverifikasi.
                                                        </p>
                                                        <small class="text-muted d-block">
                                                            <i class="fas fa-info-circle text-primary me-1"></i>Sisa tagihan (Pelunasan 50%) dapat dibayarkan saat pengerjaan berlangsung atau sebelum produk diserahkan/diantar.
                                                        </small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        @if($order->hasPendingDpPayment())
                                            <div class="alert alert-warning mb-0 py-2 text-center border-0 shadow-sm">
                                                <i class="fas fa-hourglass-half me-1"></i>Bukti pembayaran Down Payment (DP) sedang diverifikasi admin.
                                            </div>
                                        @elseif($order->status === 'confirmed')
                                            <div class="text-center">
                                                <a href="{{ route('customer.payments.create', $order->id) }}" class="btn btn-warning text-dark fw-bold btn-lg shadow-sm">
                                                    <i class="fas fa-shield-alt me-2"></i>Bayar Down Payment (DP) Rp {{ number_format($order->requiredDpAmount(), 0, ',', '.') }}
                                                </a>
                                                <small class="text-muted d-block mt-2">DP wajib dibayar agar pesanan dapat mulai dikerjakan.</small>
                                            </div>
                                        @endif
                                    @elseif(!$order->isFullyPaid())
                                        <div class="card border-0 mb-3 text-start rounded-3 shadow-sm" style="background-color: #F8FAFC; border-left: 5px solid #1B2538 !important;">
                                            <div class="card-body p-3">
                                                <div class="d-flex align-items-start">
                                                    <div class="rounded-circle bg-primary text-warning p-2 me-3 flex-shrink-0 d-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">
                                                        <i class="fas fa-receipt"></i>
                                                    </div>
                                                    <div>
                                                        <h6 class="fw-bold text-dark mb-1">Panduan Pelunasan Tagihan</h6>
                                                        <p class="mb-1 text-dark small" style="line-height: 1.5;">
                                                            Pesanan Anda saat ini sedang atau telah dikerjakan di bengkel. <strong>Penting:</strong> Sesuai ketentuan bengkel, admin hanya dapat mengubah status pesanan menjadi <strong>"Selesai"</strong> dan menyerahkan/mengirimkan produk setelah seluruh sisa tagihan (Pelunasan) terbayar 100% dan diverifikasi.
                                                        </p>
                                                        <small class="text-muted d-block">
                                                            <i class="fas fa-check-circle text-success me-1"></i>Lakukan pelunasan lebih awal agar saat pengerjaan fisik selesai, produk dapat langsung diambil atau dikirim tanpa kendala.
                                                        </small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        @if($order->hasPendingFullPayment())
                                            <div class="alert alert-info mb-0 py-2 text-center border-0 shadow-sm">
                                                <i class="fas fa-hourglass-half me-1"></i>Bukti pembayaran Pelunasan sedang diverifikasi admin.
                                            </div>
                                        @else
                                            <div class="text-center">
                                                <a href="{{ route('customer.payments.create', $order->id) }}" class="btn btn-primary btn-lg fw-bold shadow-sm">
                                                    <i class="fas fa-credit-card me-2"></i>Bayar Pelunasan (Sisa: Rp {{ number_format($order->remainingBalance(), 0, ',', '.') }})
                                                </a>
                                                <small class="text-muted d-block mt-2">Lakukan pelunasan sebelum serah terima produk.</small>
                                            </div>
                                        @endif
                                    @else
                                        <div class="alert alert-success border-0 shadow-sm mb-0 py-3 text-center">
                                            <i class="fas fa-check-circle fa-2x text-success mb-2 d-block"></i>
                                            <h6 class="fw-bold text-dark mb-1">Pembayaran Lunas 100%</h6>
                                            <p class="small text-dark mb-0">
                                                Seluruh pembayaran telah lunas terverifikasi. Begitu teknisi menyelesaikan pengerjaan fisik 100%, admin akan mengubah status menjadi <strong>"Selesai"</strong> dan produk siap diambil atau diantarkan.
                                            </p>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Timeline -->
                <div class="col-lg-4 wow fadeInUp" data-wow-delay="0.3s">
                    @if(in_array($order->status, ['in_production', 'completed']))
                        @php
                            $wp = $order->workProgress();
                        @endphp
                        <div class="card mb-4 border-0 shadow-sm">
                            <div class="card-header bg-dark text-white">
                                <h6 class="mb-0"><i class="fas fa-hard-hat text-warning me-2"></i>Progres Fisik Pengerjaan</h6>
                            </div>
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="fw-bold">{{ $wp->stepName() }}</span>
                                    <span class="badge bg-primary fs-6">{{ $order->progress_percentage }}%</span>
                                </div>
                                <div class="progress mb-2" style="height: 10px;">
                                    <div class="progress-bar progress-bar-striped progress-bar-animated bg-{{ $wp->badgeClass() }}"
                                        role="progressbar"
                                        style="width: {{ $order->progress_percentage }}%;"
                                        aria-valuenow="{{ $order->progress_percentage }}"
                                        aria-valuemin="0"
                                        aria-valuemax="100">
                                    </div>
                                </div>
                                <small class="text-muted d-block">{{ $wp->label() }}</small>
                                @if($order->progress_notes)
                                    <div class="alert alert-light border mt-2 mb-0 py-2 px-3 small">
                                        <strong>Catatan Teknisi:</strong><br>{{ $order->progress_notes }}
                                    </div>
                                @endif

                                @if($order->progress_photo_path)
                                    <div class="mt-3 pt-3 border-top">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <span class="small fw-bold text-dark"><i class="fas fa-camera text-primary me-1"></i>Dokumentasi Pengerjaan:</span>
                                            <span class="badge bg-success" style="font-size: 0.7rem;">Foto Teknisi</span>
                                        </div>
                                        <a href="#" data-bs-toggle="modal" data-bs-target="#customerProgressPhotoModal" title="Klik untuk memperbesar foto">
                                            <img src="{{ asset('storage/' . $order->progress_photo_path) }}"
                                                 alt="Foto Dokumentasi Pengerjaan"
                                                 class="img-fluid rounded border shadow-sm w-100"
                                                 style="max-height: 180px; object-fit: cover; transition: 0.3s;"
                                                 onmouseover="this.style.opacity=0.85" onmouseout="this.style.opacity=1">
                                        </a>
                                        <div class="text-center mt-1">
                                            <small class="text-muted" style="font-size: 0.75rem;">
                                                <i class="fas fa-search-plus me-1"></i>Klik foto untuk memperbesar
                                            </small>
                                        </div>
                                    </div>

                                    <!-- Customer Progress Photo Modal -->
                                    <div class="modal fade" id="customerProgressPhotoModal" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-lg modal-dialog-centered">
                                            <div class="modal-content border-0 shadow-lg">
                                                <div class="modal-header bg-dark text-white">
                                                    <h6 class="modal-title fw-bold">
                                                        <i class="fas fa-camera text-warning me-2"></i>Dokumentasi Fisik Pengerjaan - {{ $order->order_code }}
                                                    </h6>
                                                    <button type="button" class="btn-close btn-close-white shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body text-center p-3 bg-light">
                                                    <img src="{{ asset('storage/' . $order->progress_photo_path) }}" class="img-fluid rounded shadow" alt="Dokumentasi Pengerjaan">
                                                    @if($order->progress_notes)
                                                        <div class="text-muted mt-3 mb-0 small text-start bg-white p-3 rounded border">
                                                            <strong>Catatan Teknisi:</strong><br>{{ $order->progress_notes }}
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0"><i class="fas fa-tasks me-2"></i>Progress Pesanan</h5>
                        </div>
                        <div class="card-body">
                            @php
                                $statuses = ['pending', 'confirmed', 'in_production', 'completed'];
                                $statusInfo = [
                                    'pending' => ['icon' => 'fa-clock', 'color' => 'warning', 'label' => 'Pending', 'desc' => 'Pesanan diterima, menunggu konfirmasi'],
                                    'confirmed' => ['icon' => 'fa-check-circle', 'color' => 'info', 'label' => 'Dikonfirmasi', 'desc' => 'Pesanan dikonfirmasi, total harga ditetapkan'],
                                    'in_production' => ['icon' => 'fa-hammer', 'color' => 'primary', 'label' => 'Dalam Pengerjaan', 'desc' => 'Sedang dikerjakan oleh tim'],
                                    'completed' => ['icon' => 'fa-check-double', 'color' => 'success', 'label' => 'Selesai', 'desc' => 'Pesanan selesai'],
                                ];
                                $currentIndex = array_search($order->status, $statuses);
                                if($order->status === 'cancelled') $currentIndex = -1;
                            @endphp

                            <div class="timeline">
                                @foreach($statuses as $index => $status)
                                    @php
                                        $info = $statusInfo[$status];
                                        $isActive = $index <= $currentIndex;
                                        $isCurrent = $order->status === $status;
                                    @endphp
                                    <div class="d-flex mb-4">
                                        <div class="flex-shrink-0">
                                            <div class="rounded-circle d-flex align-items-center justify-content-center {{ $isActive ? 'bg-' . $info['color'] : 'bg-light' }}"
                                                style="width: 40px; height: 40px;">
                                                <i class="fas {{ $info['icon'] }} {{ $isActive ? 'text-white' : 'text-muted' }}"></i>
                                            </div>
                                        </div>
                                        <div class="ms-3">
                                            <h6 class="mb-0 {{ $isActive ? '' : 'text-muted' }}">{{ $info['label'] }}</h6>
                                            <small class="{{ $isActive ? 'text-dark' : 'text-muted' }}">{{ $info['desc'] }}</small>
                                            @if($isCurrent)
                                                <br><span class="badge bg-{{ $info['color'] }} mt-1">Status Saat Ini</span>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach

                                @if($order->status === 'cancelled')
                                    <div class="d-flex mb-4">
                                        <div class="flex-shrink-0">
                                            <div class="rounded-circle d-flex align-items-center justify-content-center bg-danger"
                                                style="width: 40px; height: 40px;">
                                                <i class="fas fa-times text-white"></i>
                                            </div>
                                        </div>
                                        <div class="ms-3">
                                            <h6 class="mb-0 text-danger">Dibatalkan</h6>
                                            <small class="text-danger">Pesanan dibatalkan</small>
                                            <br><span class="badge bg-danger mt-1">Status Saat Ini</span>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="card mt-3">
                        <div class="card-body text-center">
                            <a href="{{ route('customer.orders.index') }}" class="btn btn-outline-primary w-100 mb-2">
                                <i class="fas fa-arrow-left me-2"></i>Kembali ke Daftar Pesanan
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Order Detail End -->
@endsection

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
                                    <th>Nama Produk</th>
                                    <td>{{ $order->product_name }}</td>
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
                                    <td>{{ $order->quantity }}</td>
                                </tr>
                                <tr>
                                    <th>Total Harga</th>
                                    <td>
                                        @if($order->total_price)
                                            <span class="fs-5 fw-bold text-primary">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                                        @else
                                            <span class="text-muted">Menunggu konfirmasi admin</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Tanggal Pesanan</th>
                                    <td>{{ $order->created_at->format('d F Y, H:i') }} WIB</td>
                                </tr>
                            </table>

                            @if($order->reference_design_path)
                                <div class="mt-3">
                                    <h6 class="fw-bold">Referensi Desain:</h6>
                                    <a href="{{ asset('storage/' . $order->reference_design_path) }}" target="_blank" title="Klik untuk memperbesar">
                                        <img class="img-fluid rounded border" src="{{ asset('storage/' . $order->reference_design_path) }}" alt="Referensi Desain" style="max-height: 300px; transition: 0.3s;" onmouseover="this.style.opacity=0.8" onmouseout="this.style.opacity=1">
                                    </a>
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
                                <div class="d-flex justify-content-between align-items-center border-bottom py-3">
                                    <div>
                                        <strong>Rp {{ number_format($payment->amount, 0, ',', '.') }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $payment->bank_name }} - {{ $payment->account_name }}</small>
                                        <br>
                                        <small class="text-muted">{{ $payment->created_at->format('d M Y, H:i') }}</small>
                                    </div>
                                    <div>
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
                            @empty
                                <p class="text-muted text-center py-3 mb-0">Belum ada pembayaran.</p>
                            @endforelse

                            @if($order->status === 'confirmed' && (!isset($order->payments) || $order->payments->where('status', 'verified')->isEmpty()))
                                <div class="text-center mt-3">
                                    <a href="{{ route('customer.payments.create', $order->id) }}" class="btn btn-primary">
                                        <i class="fas fa-upload me-2"></i>Upload Bukti Pembayaran
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Timeline -->
                <div class="col-lg-4 wow fadeInUp" data-wow-delay="0.3s">
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

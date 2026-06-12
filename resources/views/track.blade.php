@extends('layouts.app')

@section('title', 'Lacak Pesanan - Bengkel Asyraf')

@section('content')
    <!-- Page Header -->
    <x-page-header title="Lacak Pesanan" :breadcrumbs="['Lacak Pesanan' => null]" />

    <!-- Track Order Start -->
    <div class="container-fluid py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 wow fadeInUp" data-wow-delay="0.1s">
                    <!-- Search Form -->
                    <div class="bg-light p-5 mb-5">
                        <h3 class="text-uppercase text-center mb-4">Masukkan Kode Pesanan</h3>
                        <form method="GET" action="{{ route('orders.track') }}">
                            <div class="input-group">
                                <input type="text" class="form-control form-control-lg border-0"
                                    name="order_code" value="{{ request('order_code') }}"
                                    placeholder="Contoh: ORD-20260611-XXXXX" required>
                                <button type="submit" class="btn btn-primary px-4">
                                    <i class="fas fa-search me-2"></i>Lacak
                                </button>
                            </div>
                        </form>
                    </div>

                    @if(request('order_code'))
                        @if(isset($order) && $order)
                            <!-- Order Found -->
                            <div class="card mb-4 wow fadeInUp" data-wow-delay="0.2s">
                                <div class="card-header bg-primary text-white">
                                    <h5 class="mb-0"><i class="fas fa-clipboard-check me-2"></i>Pesanan Ditemukan</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <table class="table table-borderless">
                                                <tr>
                                                    <th>Kode Pesanan</th>
                                                    <td><span class="fw-bold text-primary">{{ $order->order_code }}</span></td>
                                                </tr>
                                                <tr>
                                                    <th>Produk</th>
                                                    <td>{{ $order->product_name }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Tanggal</th>
                                                    <td>{{ $order->created_at->format('d F Y') }}</td>
                                                </tr>
                                            </table>
                                        </div>
                                        <div class="col-md-6">
                                            <table class="table table-borderless">
                                                <tr>
                                                    <th>Status</th>
                                                    <td>
                                                        @php
                                                            $statusColors = [
                                                                'pending' => 'warning',
                                                                'confirmed' => 'info',
                                                                'in_production' => 'primary',
                                                                'completed' => 'success',
                                                                'cancelled' => 'danger',
                                                            ];
                                                            $statusLabels = [
                                                                'pending' => 'Pending',
                                                                'confirmed' => 'Dikonfirmasi',
                                                                'in_production' => 'Dalam Pengerjaan',
                                                                'completed' => 'Selesai',
                                                                'cancelled' => 'Dibatalkan',
                                                            ];
                                                        @endphp
                                                        <span class="badge bg-{{ $statusColors[$order->status] ?? 'secondary' }} fs-6">
                                                            {{ $statusLabels[$order->status] ?? $order->status }}
                                                        </span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>Total Harga</th>
                                                    <td>
                                                        @if($order->total_price)
                                                            <span class="fw-bold">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                                                        @else
                                                            <span class="text-muted">Menunggu konfirmasi</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Timeline -->
                            <div class="card wow fadeInUp" data-wow-delay="0.3s">
                                <div class="card-header bg-dark text-white">
                                    <h5 class="mb-0"><i class="fas fa-tasks me-2"></i>Progress Pesanan</h5>
                                </div>
                                <div class="card-body p-4">
                                    @php
                                        $statuses = ['pending', 'confirmed', 'in_production', 'completed'];
                                        $statusInfo = [
                                            'pending' => ['icon' => 'fa-clock', 'color' => 'warning', 'label' => 'Pending', 'desc' => 'Pesanan diterima, menunggu konfirmasi'],
                                            'confirmed' => ['icon' => 'fa-check-circle', 'color' => 'info', 'label' => 'Dikonfirmasi', 'desc' => 'Pesanan dikonfirmasi, total harga ditetapkan'],
                                            'in_production' => ['icon' => 'fa-hammer', 'color' => 'primary', 'label' => 'Dalam Pengerjaan', 'desc' => 'Sedang dikerjakan oleh tim'],
                                            'completed' => ['icon' => 'fa-check-double', 'color' => 'success', 'label' => 'Selesai', 'desc' => 'Pesanan selesai, siap diambil/diantar'],
                                        ];
                                        $currentIndex = array_search($order->status, $statuses);
                                        if($order->status === 'cancelled') $currentIndex = -1;
                                    @endphp

                                    @foreach($statuses as $index => $status)
                                        @php
                                            $info = $statusInfo[$status];
                                            $isActive = $index <= $currentIndex;
                                            $isCurrent = $order->status === $status;
                                        @endphp
                                        <div class="d-flex mb-4 {{ !$loop->last ? 'pb-3 border-bottom' : '' }}">
                                            <div class="flex-shrink-0">
                                                <div class="rounded-circle d-flex align-items-center justify-content-center {{ $isActive ? 'bg-' . $info['color'] : 'bg-light' }}"
                                                    style="width: 50px; height: 50px;">
                                                    <i class="fas {{ $info['icon'] }} {{ $isActive ? 'text-white' : 'text-muted' }} fa-lg"></i>
                                                </div>
                                            </div>
                                            <div class="ms-3">
                                                <h5 class="mb-1 {{ $isActive ? '' : 'text-muted' }}">{{ $info['label'] }}</h5>
                                                <p class="mb-0 {{ $isActive ? 'text-dark' : 'text-muted' }}">{{ $info['desc'] }}</p>
                                                @if($isCurrent)
                                                    <span class="badge bg-{{ $info['color'] }} mt-2">Status Saat Ini</span>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach

                                    @if($order->status === 'cancelled')
                                        <div class="d-flex mb-0">
                                            <div class="flex-shrink-0">
                                                <div class="rounded-circle d-flex align-items-center justify-content-center bg-danger"
                                                    style="width: 50px; height: 50px;">
                                                    <i class="fas fa-times text-white fa-lg"></i>
                                                </div>
                                            </div>
                                            <div class="ms-3">
                                                <h5 class="mb-1 text-danger">Dibatalkan</h5>
                                                <p class="mb-0 text-danger">Pesanan ini telah dibatalkan</p>
                                                <span class="badge bg-danger mt-2">Status Saat Ini</span>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @else
                            <!-- Order Not Found -->
                            <div class="alert alert-warning text-center wow fadeInUp" data-wow-delay="0.2s">
                                <i class="fas fa-exclamation-triangle fa-2x mb-3"></i>
                                <h5>Pesanan Tidak Ditemukan</h5>
                                <p class="mb-0">Kode pesanan <strong>{{ request('order_code') }}</strong> tidak ditemukan. Pastikan kode pesanan yang Anda masukkan benar.</p>
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!-- Track Order End -->
@endsection

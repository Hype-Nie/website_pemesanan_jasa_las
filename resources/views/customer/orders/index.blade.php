@extends('layouts.app')

@section('title', 'Pesanan Saya - Bengkel Asyraf')

@section('content')
    <!-- Page Header -->
    <x-page-header title="Pesanan Saya" :breadcrumbs="['Pesanan Saya' => null]" />

    <!-- Orders List Start -->
    <div class="container-fluid py-5">
        <div class="container">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Status Filter -->
            <div class="d-flex flex-wrap gap-2 mb-4 wow fadeInUp" data-wow-delay="0.1s">
                <a href="{{ route('customer.orders.index') }}" class="btn btn-sm {{ !request('status') ? 'btn-primary' : 'btn-outline-primary' }}">Semua</a>
                <a href="{{ route('customer.orders.index', ['status' => 'pending']) }}" class="btn btn-sm {{ request('status') == 'pending' ? 'btn-warning' : 'btn-outline-warning' }}">Pending</a>
                <a href="{{ route('customer.orders.index', ['status' => 'confirmed']) }}" class="btn btn-sm {{ request('status') == 'confirmed' ? 'btn-info' : 'btn-outline-info' }}">Dikonfirmasi</a>
                <a href="{{ route('customer.orders.index', ['status' => 'in_production']) }}" class="btn btn-sm {{ request('status') == 'in_production' ? 'btn-primary' : 'btn-outline-primary' }}">Diproses</a>
                <a href="{{ route('customer.orders.index', ['status' => 'completed']) }}" class="btn btn-sm {{ request('status') == 'completed' ? 'btn-success' : 'btn-outline-success' }}">Selesai</a>
                <a href="{{ route('customer.orders.index', ['status' => 'cancelled']) }}" class="btn btn-sm {{ request('status') == 'cancelled' ? 'btn-danger' : 'btn-outline-danger' }}">Dibatalkan</a>
            </div>

            <!-- Orders -->
            @forelse($orders ?? [] as $order)
                <div class="card mb-3 wow fadeInUp" data-wow-delay="0.1s">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-2">
                                <span class="fw-bold text-primary">{{ $order->order_code }}</span>
                                <br>
                                <small class="text-muted">{{ $order->created_at->format('d M Y') }}</small>
                            </div>
                            <div class="col-md-3">
                                <h6 class="mb-0">{{ $order->product_name }}</h6>
                                <small class="text-muted">Qty: {{ $order->quantity }}</small>
                            </div>
                            <div class="col-md-2">
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
                                        'in_production' => 'Diproses',
                                        'completed' => 'Selesai',
                                        'cancelled' => 'Dibatalkan',
                                    ];
                                @endphp
                                <span class="badge bg-{{ $statusColors[$order->status] ?? 'secondary' }}">
                                    {{ $statusLabels[$order->status] ?? $order->status }}
                                </span>
                            </div>
                            <div class="col-md-2">
                                @if($order->total_price)
                                    <span class="fw-bold">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                                @else
                                    <span class="text-muted">Menunggu konfirmasi</span>
                                @endif
                            </div>
                            <div class="col-md-3 text-end">
                                <a href="{{ route('customer.orders.show', $order->id) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye me-1"></i>Lihat Detail
                                </a>
                                @if($order->status === 'confirmed')
                                    <a href="{{ route('customer.payments.create', $order->id) }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-credit-card me-1"></i>Bayar
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-5">
                    <i class="fas fa-clipboard-list fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">Belum ada pesanan.</h5>
                    <p class="text-muted">Buat pesanan pertama Anda sekarang!</p>
                    <a href="{{ route('customer.orders.create') }}" class="btn btn-primary mt-2">Buat Pesanan</a>
                </div>
            @endforelse

            <!-- Pagination -->
            @if(isset($orders) && $orders instanceof \Illuminate\Pagination\LengthAwarePaginator)
                <div class="d-flex justify-content-center mt-4">
                    {{ $orders->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
    <!-- Orders List End -->
@endsection

@extends('layouts.app')

@section('title', $product->name . ' - Bengkel Asyraf')

@section('content')
    <!-- Page Header -->
    <x-page-header title="{{ $product->name }}" :breadcrumbs="['Katalog' => route('catalog.index'), $product->name => null]" />

    <!-- Product Detail Start -->
    <div class="container-fluid py-5">
        <div class="container">
            <div class="row g-5 wow fadeInUp" data-wow-delay="0.1s">
                <!-- Product Image -->
                <div class="col-lg-6">
                    @if($product->image_path)
                        <img class="img-fluid w-100" src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}">
                    @else
                        <img class="img-fluid w-100" src="{{ asset('img/service-1.jpg') }}" alt="{{ $product->name }}">
                    @endif
                </div>

                <!-- Product Info -->
                <div class="col-lg-6">
                    <span class="badge bg-primary mb-3 fs-6">{{ ucfirst($product->category) }}</span>
                    <h2 class="text-uppercase mb-3">{{ $product->name }}</h2>
                    <p class="fs-5 mb-4">{{ $product->description }}</p>

                    <table class="table">
                        <tr>
                            <th width="40%"><i class="fas fa-tag me-2 text-primary"></i>Kategori</th>
                            <td>{{ ucfirst($product->category) }}</td>
                        </tr>
                        <tr>
                            <th><i class="fas fa-cubes me-2 text-primary"></i>Material</th>
                            <td>{{ $product->material ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th><i class="fas fa-money-bill-wave me-2 text-primary"></i>Estimasi Harga</th>
                            <td class="text-primary fw-bold fs-5">Rp {{ number_format($product->price_estimate, 0, ',', '.') }}</td>
                        </tr>
                    </table>

                    <p class="text-muted small"><i class="fas fa-info-circle me-1"></i>Harga di atas adalah estimasi. Harga final akan dikonfirmasi setelah survey dan konsultasi.</p>

                    <div class="mt-4">
                        <a href="{{ route('customer.orders.create', ['product_id' => $product->id]) }}" class="btn btn-primary py-3 px-5">
                            <i class="fas fa-clipboard-list me-2"></i>Pesan Produk Ini
                        </a>
                        <a href="{{ route('catalog.index') }}" class="btn btn-outline-primary py-3 px-4 ms-2">
                            <i class="fas fa-arrow-left me-2"></i>Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Product Detail End -->
@endsection

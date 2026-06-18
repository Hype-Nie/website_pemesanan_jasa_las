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
                    <a href="#" data-bs-toggle="modal" data-bs-target="#imageModal{{ $product->id }}">
                        @if($product->image_path)
                            <img class="img-fluid w-100 rounded" src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}" style="cursor: zoom-in;">
                        @else
                            <img class="img-fluid w-100 rounded" src="{{ asset('img/service-1.jpg') }}" alt="{{ $product->name }}" style="cursor: zoom-in;">
                        @endif
                    </a>

                    <!-- Modal Preview -->
                    <div class="modal fade" id="imageModal{{ $product->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-centered">
                            <div class="modal-content border-0 shadow-lg">
                                <div class="modal-header border-0 pb-0">
                                    <h5 class="modal-title text-muted fw-bold fs-6">{{ $product->name }}</h5>
                                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body text-center pt-2 pb-4">
                                    @if($product->image_path)
                                        <img class="img-fluid rounded" src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}" style="max-height: 80vh;">
                                    @else
                                        <img class="img-fluid rounded" src="{{ asset('img/service-1.jpg') }}" alt="{{ $product->name }}" style="max-height: 80vh;">
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Product Info -->
                <div class="col-lg-6">
                    <span class="badge bg-primary mb-3 fs-6">{{ $product->category->name ?? 'Uncategorized' }}</span>
                    <h2 class="text-uppercase mb-3">{{ $product->name }}</h2>
                    <p class="fs-5 mb-4">{{ $product->description }}</p>

                    <table class="table">
                        <tr>
                            <th width="40%"><i class="fas fa-tag me-2 text-primary"></i>Kategori</th>
                            <td>{{ $product->category->name ?? 'Uncategorized' }}</td>
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
                        <a href="{{ route('customer.orders.create', $product->id) }}" class="btn btn-primary py-3 px-5">
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

@extends('layouts.app')

@section('title', 'Katalog Produk - Bengkel Asyraf')

@section('content')
    <!-- Page Header -->
    <x-page-header title="Katalog Produk" :breadcrumbs="['Katalog' => null]" />

    <!-- Catalog Start -->
    <div class="container-fluid service py-5">
        <div class="container">
            <!-- Category Filter -->
            <div class="text-center mb-5 wow fadeInUp" data-wow-delay="0.1s">
                <div class="d-flex flex-wrap justify-content-center gap-2">
                    <a href="{{ route('catalog.index') }}" class="btn {{ !request('category') ? 'btn-primary' : 'btn-outline-primary' }}">Semua</a>
                    @foreach($categories as $cat)
                        <a href="{{ route('catalog.index', ['category' => $cat->id]) }}" class="btn {{ request('category') == $cat->id ? 'btn-primary' : 'btn-outline-primary' }}">{{ $cat->name }}</a>
                    @endforeach
                </div>
            </div>

            <!-- Product Grid -->
            <div class="row g-4">
                @forelse($products ?? [] as $index => $product)
                    <div class="col-lg-3 col-md-6 wow fadeInUp" data-wow-delay="{{ 0.1 + ($index % 4) * 0.1 }}s">
                        <div class="service-item">
                            <div class="service-inner pb-5">
                                <a href="#" data-bs-toggle="modal" data-bs-target="#imageModal{{ $product->id }}">
                                    @if($product->image_path)
                                        <img class="img-fluid w-100" src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}" style="cursor: zoom-in;">
                                    @else
                                        <img class="img-fluid w-100" src="{{ asset('img/service-' . (($index % 8) + 1) . '.jpg') }}" alt="{{ $product->name }}" style="cursor: zoom-in;">
                                    @endif
                                </a>


                                <div class="service-text px-5 pt-4">
                                    <span class="badge bg-primary mb-2">{{ $product->category->name ?? 'Uncategorized' }}</span>
                                    <h5 class="text-uppercase">{{ $product->name }}</h5>
                                    <p>{{ Str::limit($product->description, 60) }}</p>
                                    <p class="text-primary fw-bold fs-5">Rp {{ number_format($product->price_estimate, 0, ',', '.') }}</p>
                                </div>
                                <div class="d-flex justify-content-center gap-2">
                                    <a class="btn btn-light px-3" href="{{ route('catalog.show', $product->id) }}">Detail<i class="bi bi-chevron-double-right ms-1"></i></a>
                                    <a class="btn btn-primary px-3" href="{{ route('customer.orders.create', $product->id) }}">Pesan</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Belum ada produk dalam katalog.</h5>
                        <p class="text-muted">Silakan hubungi kami untuk pemesanan custom.</p>
                        <a href="{{ route('customer.orders.create') }}" class="btn btn-primary mt-2">Pesan Custom</a>
                    </div>
                @endforelse
            </div>

            @foreach($products ?? [] as $index => $product)
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
                                    <img class="img-fluid rounded" src="{{ asset('img/service-' . (($index % 8) + 1) . '.jpg') }}" alt="{{ $product->name }}" style="max-height: 80vh;">
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

            <!-- Pagination -->
            @if(isset($products) && $products instanceof \Illuminate\Pagination\LengthAwarePaginator)
                <div class="d-flex justify-content-center mt-5">
                    {{ $products->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
    <!-- Catalog End -->
@endsection

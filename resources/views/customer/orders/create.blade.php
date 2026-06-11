@extends('layouts.app')

@section('title', 'Form Pemesanan - Bengkel Asyraf')

@section('content')
    <!-- Page Header -->
    <x-page-header title="Form Pemesanan Custom" :breadcrumbs="['Pemesanan' => null]" />

    <!-- Order Form Start -->
    <div class="container-fluid py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 wow fadeInUp" data-wow-delay="0.1s">

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    {{-- Product info card if product_id passed --}}
                    @if(isset($product))
                        <div class="card border-primary mb-4">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-md-3">
                                        @if($product->image)
                                            <img class="img-fluid" src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}">
                                        @else
                                            <img class="img-fluid" src="{{ asset('img/service-1.jpg') }}" alt="{{ $product->name }}">
                                        @endif
                                    </div>
                                    <div class="col-md-9">
                                        <h5 class="text-uppercase">{{ $product->name }}</h5>
                                        <span class="badge bg-primary">{{ ucfirst($product->category) }}</span>
                                        <p class="mt-2 mb-1">{{ Str::limit($product->description, 100) }}</p>
                                        <p class="text-primary fw-bold">Estimasi: Rp {{ number_format($product->price_estimate, 0, ',', '.') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="bg-light p-5">
                        <h3 class="text-uppercase mb-4">Detail Pesanan</h3>

                        <form method="POST" action="{{ route('customer.orders.store') }}" enctype="multipart/form-data">
                            @csrf

                            @if(isset($product))
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                            @endif

                            <div class="form-floating mb-3">
                                <input type="text" class="form-control border-0 @error('product_name') is-invalid @enderror"
                                    id="product_name" name="product_name"
                                    value="{{ old('product_name', isset($product) ? $product->name : '') }}"
                                    placeholder="Nama Produk" required>
                                <label for="product_name">Nama Produk / Jenis Pekerjaan</label>
                                @error('product_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-floating mb-3">
                                <textarea class="form-control border-0 @error('description') is-invalid @enderror"
                                    id="description" name="description" placeholder="Deskripsi" style="height: 120px"
                                    required>{{ old('description') }}</textarea>
                                <label for="description">Deskripsi Detail Pesanan</label>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="text" class="form-control border-0 @error('dimensions') is-invalid @enderror"
                                            id="dimensions" name="dimensions" value="{{ old('dimensions') }}"
                                            placeholder="Ukuran">
                                        <label for="dimensions">Ukuran (contoh: 2m x 1.5m)</label>
                                        @error('dimensions')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="text" class="form-control border-0 @error('material_preference') is-invalid @enderror"
                                            id="material_preference" name="material_preference" value="{{ old('material_preference') }}"
                                            placeholder="Material">
                                        <label for="material_preference">Preferensi Material</label>
                                        @error('material_preference')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-floating mb-3">
                                <input type="number" class="form-control border-0 @error('quantity') is-invalid @enderror"
                                    id="quantity" name="quantity" value="{{ old('quantity', 1) }}"
                                    placeholder="Jumlah" min="1" required>
                                <label for="quantity">Jumlah</label>
                                @error('quantity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="reference_design" class="form-label fw-bold">Referensi Desain (opsional)</label>
                                <input type="file" class="form-control @error('reference_design') is-invalid @enderror"
                                    id="reference_design" name="reference_design" accept="image/*">
                                <small class="text-muted">Upload gambar referensi desain yang diinginkan (JPG, PNG, max 2MB)</small>
                                @error('reference_design')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary w-100 py-3">
                                <i class="fas fa-paper-plane me-2"></i>Kirim Pesanan
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Order Form End -->
@endsection

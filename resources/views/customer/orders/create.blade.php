@extends('layouts.app')

@section('title', (isset($catalogProduct) ? 'Pesan ' . $catalogProduct->name : 'Form Pemesanan Custom') . ' - Bengkel Asyraf')

@section('content')
    <!-- Page Header -->
    <x-page-header 
        :title="isset($catalogProduct) ? 'Pemesanan Produk Katalog' : 'Form Pemesanan Custom'" 
        :breadcrumbs="isset($catalogProduct) 
            ? ['Katalog' => route('catalog.index'), $catalogProduct->name => route('catalog.show', $catalogProduct->id), 'Pesan' => null] 
            : ['Pemesanan Custom' => null]" 
    />

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

                    {{-- Product info card if catalog product is selected --}}
                    @if(isset($catalogProduct))
                        <div class="card border-0 shadow-sm mb-4 rounded-3 overflow-hidden" style="border-left: 5px solid #1B2538 !important; background-color: #F8FAFC;">
                            <div class="card-body p-4">
                                <div class="row align-items-center g-3">
                                    <div class="col-md-4 text-center">
                                        @if($catalogProduct->image_path)
                                            <img class="img-fluid rounded border shadow-sm" src="{{ asset('storage/' . $catalogProduct->image_path) }}" alt="{{ $catalogProduct->name }}" style="max-height: 180px; width: 100%; object-fit: cover;">
                                        @else
                                            <img class="img-fluid rounded border shadow-sm" src="{{ asset('img/service-1.jpg') }}" alt="{{ $catalogProduct->name }}" style="max-height: 180px; width: 100%; object-fit: cover;">
                                        @endif
                                    </div>
                                    <div class="col-md-8">
                                        <div class="d-flex align-items-center justify-content-between mb-1">
                                            <span class="badge bg-primary text-white">{{ $catalogProduct->category->name ?? 'Katalog' }}</span>
                                            <span class="badge bg-success"><i class="fas fa-check-circle me-1"></i>Harga Pasti Katalog</span>
                                        </div>
                                        <h4 class="text-uppercase fw-bold text-dark mb-1">{{ $catalogProduct->name }}</h4>
                                        <p class="text-muted small mb-2">{{ Str::limit($catalogProduct->description, 120) }}</p>
                                        
                                        <div class="bg-white p-3 rounded-2 border">
                                            <div class="row g-2">
                                                <div class="col-6">
                                                    <small class="text-muted d-block">Harga Satuan:</small>
                                                    <span class="fw-bold text-dark">Rp {{ number_format($catalogProduct->price_estimate, 0, ',', '.') }}</span>
                                                </div>
                                                <div class="col-6">
                                                    <small class="text-muted d-block">Material Standar:</small>
                                                    <span class="fw-bold text-dark">{{ $catalogProduct->material ?? '-' }}</span>
                                                </div>
                                                <div class="col-6 pt-2 border-top">
                                                    <small class="text-muted d-block">Estimasi Total (<span id="summary-qty-label">1</span> unit):</small>
                                                    <span class="fw-bold text-primary fs-6" id="live-total-display">Rp {{ number_format($catalogProduct->price_estimate, 0, ',', '.') }}</span>
                                                </div>
                                                <div class="col-6 pt-2 border-top">
                                                    <small class="text-muted d-block">Wajib DP (50%):</small>
                                                    <span class="fw-bold text-warning fs-6" id="live-dp-display">Rp {{ number_format($catalogProduct->price_estimate * 0.5, 0, ',', '.') }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="alert alert-info bg-white border mt-3 mb-0 py-2 px-3 small text-dark">
                                    <i class="fas fa-info-circle text-primary me-2"></i>
                                    Pesanan dari katalog akan langsung berstatus <strong>Dikonfirmasi</strong> dengan total harga di atas. Anda dapat langsung membayar Down Payment (DP 50%) tanpa menunggu persetujuan admin.
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="bg-light p-4 p-md-5 rounded-3 shadow-sm">
                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <h3 class="text-uppercase mb-0">{{ isset($catalogProduct) ? 'Spesifikasi Pemesanan' : 'Detail Pesanan Custom' }}</h3>
                            @if(isset($catalogProduct))
                                <span class="badge bg-secondary">Produk Katalog</span>
                            @else
                                <span class="badge bg-primary">Custom Order</span>
                            @endif
                        </div>

                        <form method="POST" action="{{ route('customer.orders.store') }}" enctype="multipart/form-data">
                            @csrf

                            @if(isset($catalogProduct))
                                <input type="hidden" name="catalog_product_id" value="{{ $catalogProduct->id }}">
                            @endif

                            <div class="form-floating mb-3">
                                <input type="text" class="form-control border-0 @error('product_name') is-invalid @enderror"
                                    id="product_name" name="product_name"
                                    value="{{ old('product_name', isset($catalogProduct) ? $catalogProduct->name : '') }}"
                                    placeholder="Nama Produk" required>
                                <label for="product_name">Nama Produk / Jenis Pekerjaan</label>
                                @error('product_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-floating mb-3">
                                <textarea class="form-control border-0 @error('description') is-invalid @enderror"
                                    id="description" name="description" placeholder="Deskripsi" style="height: 120px"
                                    required>{{ old('description', isset($catalogProduct) ? $catalogProduct->description : '') }}</textarea>
                                <label for="description">Deskripsi Detail Pesanan</label>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="text" class="form-control border-0 @error('dimensions') is-invalid @enderror"
                                            id="dimensions" name="dimensions" 
                                            value="{{ old('dimensions', isset($catalogProduct) ? 'Standar Katalog' : '') }}"
                                            placeholder="Ukuran" required>
                                        <label for="dimensions">Ukuran (contoh: 2m x 1.5m / Standar)</label>
                                        @error('dimensions')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="text" class="form-control border-0 @error('material_preference') is-invalid @enderror"
                                            id="material_preference" name="material_preference" 
                                            value="{{ old('material_preference', isset($catalogProduct) ? ($catalogProduct->material ?? '') : '') }}"
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
                                <label for="quantity">Jumlah (Unit / Set)</label>
                                @error('quantity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="reference_design" class="form-label fw-bold">
                                    Referensi Desain 
                                    @if(isset($catalogProduct))
                                        <span class="text-muted fw-normal">(Opsional)</span>
                                    @else
                                        <span class="text-danger">*</span>
                                    @endif
                                </label>
                                <input type="file" class="form-control @error('reference_design') is-invalid @enderror"
                                    id="reference_design" name="reference_design" accept="image/*,application/pdf"
                                    {{ isset($catalogProduct) ? '' : 'required' }}>
                                
                                @if(isset($catalogProduct))
                                    <small class="text-muted d-block mt-1">
                                        <i class="fas fa-camera me-1"></i>Opsional: Jika tidak diunggah, foto dari katalog produk akan otomatis digunakan sebagai gambar referensi pesanan.
                                    </small>
                                @else
                                    <small class="text-muted d-block mt-1">Upload gambar/PDF rancangan desain yang diinginkan (JPG, PNG, PDF, max 5MB)</small>
                                @endif
                                
                                <!-- Image Preview Container -->
                                <div id="preview-container" class="mt-3" style="{{ isset($catalogProduct) && $catalogProduct->image_path ? '' : 'display: none;' }}">
                                    <h6 class="fw-bold fs-6 text-muted mb-1">Preview Gambar Referensi:</h6>
                                    <img id="image-preview" 
                                        src="{{ isset($catalogProduct) && $catalogProduct->image_path ? asset('storage/' . $catalogProduct->image_path) : '#' }}" 
                                        alt="Preview" class="img-fluid rounded border shadow-sm" style="max-height: 250px;">
                                </div>

                                @error('reference_design')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary w-100 py-3 fw-bold">
                                <i class="fas fa-paper-plane me-2"></i>{{ isset($catalogProduct) ? 'Pesan Sekarang' : 'Kirim Permintaan Custom' }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Order Form End -->
@endsection

@push('scripts')
<script>
    // Live Image Preview
    document.getElementById('reference_design').addEventListener('change', function(e) {
        const file = e.target.files[0];
        const previewContainer = document.getElementById('preview-container');
        const imagePreview = document.getElementById('image-preview');

        if (file && file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function(e) {
                imagePreview.src = e.target.result;
                previewContainer.style.display = 'block';
            }
            reader.readAsDataURL(file);
        } else if (!file) {
            @if(isset($catalogProduct) && $catalogProduct->image_path)
                imagePreview.src = "{{ asset('storage/' . $catalogProduct->image_path) }}";
                previewContainer.style.display = 'block';
            @else
                imagePreview.src = '#';
                previewContainer.style.display = 'none';
            @endif
        }
    });

    @if(isset($catalogProduct))
    // Live Price Calculator for Catalog Product
    const unitPrice = {{ (float) $catalogProduct->price_estimate }};
    const qtyInput = document.getElementById('quantity');
    const totalDisplay = document.getElementById('live-total-display');
    const dpDisplay = document.getElementById('live-dp-display');
    const qtyLabel = document.getElementById('summary-qty-label');

    function updateLivePrice() {
        const qty = Math.max(1, parseInt(qtyInput.value) || 1);
        const total = unitPrice * qty;
        const dp = Math.round(total * 0.5);

        const formatter = new Intl.NumberFormat('id-ID');
        if (qtyLabel) qtyLabel.textContent = qty;
        if (totalDisplay) totalDisplay.textContent = 'Rp ' + formatter.format(total);
        if (dpDisplay) dpDisplay.textContent = 'Rp ' + formatter.format(dp);
    }

    qtyInput.addEventListener('input', updateLivePrice);
    qtyInput.addEventListener('change', updateLivePrice);
    @endif
</script>
@endpush


@extends('layouts.admin')

@section('title', 'Kelola Katalog - Admin Bengkel Asyraf')
@section('page-title', 'Kelola Katalog')

@section('content')
    <div class="card stat-card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fas fa-boxes me-2"></i>Daftar Produk</h5>
            <a href="{{ route('admin.catalog.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Tambah Produk
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th width="80">Gambar</th>
                            <th>Nama Produk</th>
                            <th>Kategori</th>
                            <th>Material</th>
                            <th>Estimasi Harga</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products ?? [] as $product)
                            <tr>
                                <td>
                                    @if($product->image_path)
                                        <a href="#" data-bs-toggle="modal" data-bs-target="#imageModal{{ $product->id }}">
                                            <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}"
                                                class="img-fluid rounded" style="width: 60px; height: 60px; object-fit: cover; cursor: zoom-in;">
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
                                                        <img src="{{ asset('storage/' . $product->image_path) }}" class="img-fluid rounded" alt="{{ $product->name }}" style="max-height: 80vh;">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <div class="bg-light rounded d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                            <i class="fas fa-image text-muted"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <strong>{{ $product->name }}</strong>
                                    <br><small class="text-muted">{{ Str::limit($product->description, 50) }}</small>
                                </td>
                                <td><span class="badge bg-primary">{{ $product->category->name ?? '-' }}</span></td>
                                <td>{{ $product->material ?? '-' }}</td>
                                <td>Rp {{ number_format($product->price_estimate, 0, ',', '.') }}</td>
                                <td>
                                    @if($product->is_active ?? true)
                                        <span class="badge bg-success">Aktif</span>
                                    @else
                                        <span class="badge bg-secondary">Nonaktif</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.catalog.edit', $product->id) }}" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form method="POST" action="{{ route('admin.catalog.destroy', $product->id) }}" class="d-inline"
                                        onsubmit="return confirm('Hapus produk ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    Belum ada produk. <a href="{{ route('admin.catalog.create') }}">Tambah produk baru</a>.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if(isset($products) && $products instanceof \Illuminate\Pagination\LengthAwarePaginator)
                <div class="d-flex justify-content-center mt-3">
                    {{ $products->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection

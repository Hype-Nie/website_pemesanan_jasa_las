@extends('layouts.admin')

@section('title', 'Detail Pesanan ' . $order->order_code . ' - Admin Bengkel Asyraf')
@section('page-title', 'Detail Pesanan')

@section('content')
    <div class="row g-4">
        <!-- Order Detail -->
        <div class="col-lg-8">
            <div class="card stat-card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-clipboard-list me-2"></i>Detail Pesanan {{ $order->order_code }}</h5>
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
                    <span class="badge bg-{{ $statusColors[$order->status] ?? 'secondary' }} fs-6">
                        {{ $statusLabels[$order->status] ?? $order->status }}
                    </span>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th>Kode Pesanan</th>
                                    <td>{{ $order->order_code }}</td>
                                </tr>
                                <tr>
                                    <th>Pelanggan</th>
                                    <td>{{ $order->user->name ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td>{{ $order->user->email ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Telepon</th>
                                    <td>{{ $order->user->phone ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Tanggal Pesan</th>
                                    <td>{{ $order->created_at->format('d F Y, H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
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
                                            <span class="fw-bold text-primary fs-5">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                                        @else
                                            <span class="text-muted">Belum ditetapkan</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if($order->reference_design_path)
                        <div class="mt-3">
                            <h6 class="fw-bold">Referensi Desain:</h6>
                            <a href="{{ asset('storage/' . $order->reference_design_path) }}" target="_blank" title="Klik untuk memperbesar">
                                <img class="img-fluid rounded border" src="{{ asset('storage/' . $order->reference_design_path) }}" alt="Referensi Desain" style="max-height: 400px; transition: 0.3s;" onmouseover="this.style.opacity=0.8" onmouseout="this.style.opacity=1">
                            </a>
                        </div>
                    @endif

                    @if($order->admin_notes)
                        <div class="alert alert-info mt-3">
                            <h6 class="fw-bold"><i class="fas fa-sticky-note me-2"></i>Catatan Admin:</h6>
                            <p class="mb-0">{{ $order->admin_notes }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Payment History -->
            <div class="card stat-card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-credit-card me-2"></i>Riwayat Pembayaran</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Bank</th>
                                    <th>Nama</th>
                                    <th>Jumlah</th>
                                    <th>Bukti</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($order->payments ?? [] as $payment)
                                    <tr>
                                        <td>{{ $payment->created_at->format('d M Y, H:i') }}</td>
                                        <td>{{ $payment->bank_name }}</td>
                                        <td>{{ $payment->account_name }}</td>
                                        <td>Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                                        <td>
                                            @if($payment->proof_image_path)
                                                <a href="{{ asset('storage/' . $payment->proof_image_path) }}" target="_blank" class="btn btn-sm btn-outline-info">
                                                    <i class="fas fa-image me-1"></i>Lihat
                                                </a>
                                            @endif
                                        </td>
                                        <td>
                                            @php
                                                $paymentColors = ['pending' => 'warning', 'verified' => 'success', 'rejected' => 'danger'];
                                                $paymentLabels = ['pending' => 'Pending', 'verified' => 'Terverifikasi', 'rejected' => 'Ditolak'];
                                            @endphp
                                            <span class="badge bg-{{ $paymentColors[$payment->status] ?? 'secondary' }}">
                                                {{ $paymentLabels[$payment->status] ?? $payment->status }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-3">Belum ada pembayaran.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Update Form -->
        <div class="col-lg-4">
            <div class="card stat-card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-edit me-2"></i>Update Pesanan</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.orders.updateStatus', $order->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="status" class="form-label fw-bold">Status Pesanan</label>
                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="confirmed" {{ $order->status == 'confirmed' ? 'selected' : '' }}>Dikonfirmasi</option>
                                <option value="in_production" {{ $order->status == 'in_production' ? 'selected' : '' }}>Dalam Pengerjaan</option>
                                <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Selesai</option>
                                <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="total_price" class="form-label fw-bold">Total Harga (Rp)</label>
                            <input type="number" class="form-control @error('total_price') is-invalid @enderror"
                                id="total_price" name="total_price"
                                value="{{ old('total_price', $order->total_price) }}" min="0">
                            @error('total_price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="admin_notes" class="form-label fw-bold">Catatan Admin</label>
                            <textarea class="form-control @error('admin_notes') is-invalid @enderror"
                                id="admin_notes" name="admin_notes" rows="4">{{ old('admin_notes', $order->admin_notes) }}</textarea>
                            @error('admin_notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-save me-2"></i>Update Pesanan
                        </button>
                    </form>
                </div>
            </div>

            <div class="card stat-card mt-3">
                <div class="card-body text-center">
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary w-100">
                        <i class="fas fa-arrow-left me-2"></i>Kembali ke Daftar Pesanan
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection

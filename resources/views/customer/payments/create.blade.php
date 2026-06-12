@extends('layouts.app')

@section('title', 'Upload Bukti Pembayaran - Bengkel Asyraf')

@section('content')
    <!-- Page Header -->
    <x-page-header title="Upload Bukti Pembayaran" :breadcrumbs="['Pesanan Saya' => route('customer.orders.index'), 'Pembayaran' => null]" />

    <!-- Payment Form Start -->
    <div class="container-fluid py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-7 wow fadeInUp" data-wow-delay="0.1s">

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <!-- Order Summary -->
                    <div class="card border-primary mb-4">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0"><i class="fas fa-clipboard-list me-2"></i>Ringkasan Pesanan</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless mb-0">
                                <tr>
                                    <th>Kode Pesanan</th>
                                    <td>{{ $order->order_code }}</td>
                                </tr>
                                <tr>
                                    <th>Produk</th>
                                    <td>{{ $order->product_name }}</td>
                                </tr>
                                <tr>
                                    <th>Total Harga</th>
                                    <td><span class="fs-5 fw-bold text-primary">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span></td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <!-- Bank Info -->
                    <div class="alert alert-info mb-4">
                        <h5 class="alert-heading"><i class="fas fa-university me-2"></i>Informasi Transfer</h5>
                        <hr>
                        <p class="mb-1"><strong>Bank:</strong> BCA</p>
                        <p class="mb-1"><strong>No. Rekening:</strong> 1234567890</p>
                        <p class="mb-1"><strong>Atas Nama:</strong> Asyraf</p>
                        <hr>
                        <p class="mb-0 small"><i class="fas fa-info-circle me-1"></i>Silakan transfer sesuai total harga pesanan, lalu upload bukti pembayaran di bawah ini.</p>
                    </div>

                    <!-- Payment Form -->
                    <div class="bg-light p-5">
                        <h4 class="text-uppercase mb-4">Upload Bukti Transfer</h4>

                        <form method="POST" action="{{ route('customer.payments.store', $order->id) }}" enctype="multipart/form-data">
                            @csrf

                            <div class="mb-3">
                                <label for="proof_image" class="form-label fw-bold">Bukti Transfer <span class="text-danger">*</span></label>
                                <input type="file" class="form-control @error('proof_image') is-invalid @enderror"
                                    id="proof_image" name="proof_image" accept="image/*" required>
                                <small class="text-muted">Upload foto/screenshot bukti transfer (JPG, PNG, max 2MB)</small>
                                @error('proof_image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-floating mb-3">
                                <input type="text" class="form-control border-0 @error('bank_name') is-invalid @enderror"
                                    id="bank_name" name="bank_name" value="{{ old('bank_name') }}"
                                    placeholder="Nama Bank" required>
                                <label for="bank_name">Nama Bank Pengirim</label>
                                @error('bank_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-floating mb-3">
                                <input type="text" class="form-control border-0 @error('account_name') is-invalid @enderror"
                                    id="account_name" name="account_name" value="{{ old('account_name') }}"
                                    placeholder="Nama Pemilik Rekening" required>
                                <label for="account_name">Nama Pemilik Rekening</label>
                                @error('account_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-floating mb-4">
                                <input type="number" class="form-control border-0 @error('amount') is-invalid @enderror"
                                    id="amount" name="amount" value="{{ old('amount') }}"
                                    placeholder="Jumlah Transfer" required>
                                <label for="amount">Jumlah Transfer (Rp)</label>
                                @error('amount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary w-100 py-3">
                                <i class="fas fa-upload me-2"></i>Kirim Bukti Pembayaran
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Payment Form End -->
@endsection

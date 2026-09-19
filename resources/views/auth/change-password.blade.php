@php
    $layout = 'layouts.app';
    if (Auth::check()) {
        if (Auth::user()->isAdmin()) {
            $layout = 'layouts.admin';
        } elseif (Auth::user()->isEmployee()) {
            $layout = 'layouts.employee';
        }
    }
@endphp

@extends($layout)

@section('title', 'Ganti Kata Sandi - Bengkel Asyraf')
@section('page-title', 'Ganti Kata Sandi')

@section('content')
    @if($layout === 'layouts.app')
        <!-- Page Header Frontend -->
        <x-page-header title="Ganti Kata Sandi" :breadcrumbs="['Akun' => null, 'Ganti Kata Sandi' => null]" />
    @endif

    <div class="{{ $layout === 'layouts.app' ? 'container-fluid py-5' : 'container-fluid p-0' }}">
        <div class="{{ $layout === 'layouts.app' ? 'container' : '' }}">
            <div class="row justify-content-center">
                <div class="col-lg-6 col-md-8">
                    <div class="card border-0 shadow-sm {{ $layout !== 'layouts.app' ? 'mt-2' : '' }}">
                        <div class="card-body p-4 p-md-5">
                            <div class="text-center mb-4">
                                <div class="rounded-circle bg-warning text-dark d-inline-flex align-items-center justify-content-center mb-3 shadow-sm"
                                    style="width: 58px; height: 58px; font-size: 1.4rem;">
                                    <i class="fas fa-key"></i>
                                </div>
                                <h4 class="fw-bold text-dark mb-1">Ganti Kata Sandi</h4>
                                <p class="text-muted small">
                                    Perbarui kata sandi akun <strong>{{ Auth::user()->email }}</strong> demi keamanan akun Anda.
                                </p>
                            </div>

                            @if(session('status'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <i class="fas fa-check-circle me-2"></i>{{ session('status') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif

                            <form method="POST" action="{{ route('password.update') }}">
                                @csrf
                                @method('PUT')

                                <div class="mb-3">
                                    <label for="current_password" class="form-label small fw-bold text-dark">Kata Sandi Saat Ini <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control @error('current_password') is-invalid @enderror"
                                        id="current_password" name="current_password" placeholder="Masukkan kata sandi saat ini" required autofocus>
                                    @error('current_password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="password" class="form-label small fw-bold text-dark">Kata Sandi Baru (Min. 8 Karakter) <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror"
                                        id="password" name="password" placeholder="Masukkan kata sandi baru" required>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label for="password_confirmation" class="form-label small fw-bold text-dark">Ulangi Kata Sandi Baru <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control"
                                        id="password_confirmation" name="password_confirmation" placeholder="Konfirmasi kata sandi baru" required>
                                </div>

                                <button type="submit" class="btn btn-warning text-dark fw-bold w-100 py-2 shadow-sm">
                                    <i class="fas fa-save me-2"></i>Simpan Kata Sandi Baru
                                </button>
                            </form>

                            <div class="text-center mt-4 pt-3 border-top">
                                @if(Auth::user()->isAdmin())
                                    <a href="{{ route('admin.dashboard') }}" class="text-muted small text-decoration-none">
                                        <i class="fas fa-arrow-left me-1"></i> Kembali ke Dashboard Admin
                                    </a>
                                @elseif(Auth::user()->isEmployee())
                                    <a href="{{ route('employee.dashboard') }}" class="text-muted small text-decoration-none">
                                        <i class="fas fa-arrow-left me-1"></i> Kembali ke Dashboard Karyawan
                                    </a>
                                @else
                                    <a href="{{ route('customer.orders.index') }}" class="text-muted small text-decoration-none">
                                        <i class="fas fa-arrow-left me-1"></i> Kembali ke Pesanan Saya
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

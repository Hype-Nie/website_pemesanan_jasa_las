@extends('layouts.app')

@section('title', 'Lupa Kata Sandi - Bengkel Asyraf')

@section('content')
    <!-- Page Header -->
    <x-page-header title="Lupa Kata Sandi" :breadcrumbs="['Lupa Kata Sandi' => null]" />

    <!-- Forgot Password Form Start -->
    <div class="container-fluid py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-5 col-md-8 wow fadeInUp" data-wow-delay="0.1s">
                    <div class="bg-light p-5 rounded shadow-sm">
                        <h3 class="text-uppercase text-center mb-3">Reset Kata Sandi</h3>
                        <p class="text-muted text-center small mb-4">
                            Masukkan alamat email akun Anda. Sistem kami akan membuatkan kata sandi acak 8 karakter baru dan mengirimkannya ke email Anda melalui SMTP.
                        </p>

                        @if(session('status'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="fas fa-check-circle me-2"></i>{{ session('status') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('password.email') }}">
                            @csrf

                            <div class="form-floating mb-3">
                                <input type="email" class="form-control border-0 @error('email') is-invalid @enderror"
                                    id="email" name="email" value="{{ old('email') }}" placeholder="Email" required autofocus>
                                <label for="email">Alamat Email Terdaftar</label>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary w-100 py-3 mb-3">
                                <i class="fas fa-paper-plane me-2"></i>Kirim Password Baru
                            </button>
                        </form>

                        <div class="text-center mt-3 border-top pt-3">
                            <p class="mb-0">Sudah ingat kata sandi Anda? <a href="{{ route('login') }}" class="text-primary fw-bold">Kembali ke Masuk</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Forgot Password Form End -->
@endsection

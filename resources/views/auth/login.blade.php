@extends('layouts.app')

@section('title', 'Masuk - Bengkel Asyraf')

@section('content')
    <!-- Page Header -->
    <x-page-header title="Masuk" :breadcrumbs="['Masuk' => null]" />

    <!-- Login Form Start -->
    <div class="container-fluid py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-5 col-md-8 wow fadeInUp" data-wow-delay="0.1s">
                    <div class="bg-light p-5">
                        <h3 class="text-uppercase text-center mb-4">Masuk ke Akun</h3>

                        @if(session('status'))
                            <div class="alert alert-success">{{ session('status') }}</div>
                        @endif

                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <div class="form-floating mb-3">
                                <input type="email" class="form-control border-0 @error('email') is-invalid @enderror"
                                    id="email" name="email" value="{{ old('email') }}" placeholder="Email" required autofocus>
                                <label for="email">Alamat Email</label>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-floating mb-3">
                                <input type="password" class="form-control border-0 @error('password') is-invalid @enderror"
                                    id="password" name="password" placeholder="Password" required>
                                <label for="password">Kata Sandi</label>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label" for="remember">Ingat Saya</label>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 py-3">Masuk</button>
                        </form>

                        <div class="text-center mt-4">
                            <p>Belum punya akun? <a href="{{ route('register') }}" class="text-primary fw-bold">Daftar Sekarang</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Login Form End -->
@endsection

@extends('layouts.app')

@section('title', 'Daftar Akun - Bengkel Asyraf')

@section('content')
    <!-- Page Header -->
    <x-page-header title="Daftar Akun" :breadcrumbs="['Daftar' => null]" />

    <!-- Register Form Start -->
    <div class="container-fluid py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6 col-md-8 wow fadeInUp" data-wow-delay="0.1s">
                    <div class="bg-light p-5">
                        <h3 class="text-uppercase text-center mb-4">Buat Akun Baru</h3>

                        <form method="POST" action="{{ route('register') }}">
                            @csrf

                            <div class="form-floating mb-3">
                                <input type="text" class="form-control border-0 @error('name') is-invalid @enderror"
                                    id="name" name="name" value="{{ old('name') }}" placeholder="Nama Lengkap" required autofocus>
                                <label for="name">Nama Lengkap</label>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-floating mb-3">
                                <input type="email" class="form-control border-0 @error('email') is-invalid @enderror"
                                    id="email" name="email" value="{{ old('email') }}" placeholder="Email" required>
                                <label for="email">Alamat Email</label>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-floating mb-3">
                                <input type="text" class="form-control border-0 @error('phone') is-invalid @enderror"
                                    id="phone" name="phone" value="{{ old('phone') }}" placeholder="No. HP">
                                <label for="phone">No. HP / WhatsApp</label>
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-floating mb-3">
                                <input type="password" class="form-control border-0 @error('password') is-invalid @enderror"
                                    id="password" name="password" placeholder="Kata Sandi" required>
                                <label for="password">Kata Sandi</label>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-floating mb-4">
                                <input type="password" class="form-control border-0"
                                    id="password_confirmation" name="password_confirmation" placeholder="Konfirmasi Kata Sandi" required>
                                <label for="password_confirmation">Konfirmasi Kata Sandi</label>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 py-3">Daftar</button>
                        </form>

                        <div class="text-center mt-4">
                            <p>Sudah punya akun? <a href="{{ route('login') }}" class="text-primary fw-bold">Masuk di sini</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Register Form End -->
@endsection

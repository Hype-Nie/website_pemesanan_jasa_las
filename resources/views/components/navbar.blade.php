<div class="container-fluid bg-white sticky-top wow fadeIn" data-wow-delay="0.1s">
    <div class="container">
        <nav class="navbar navbar-expand-lg bg-white navbar-light p-lg-0">
            <a href="{{ route('home') }}" class="navbar-brand d-flex align-items-center d-lg-none">
                <img src="{{ asset('img/logo.jpeg') }}" alt="Logo Bengkel Asyraf" style="height: 40px; width: 40px; object-fit: cover; border-radius: 5px;" class="me-2">
                <h1 class="fw-bold m-0" style="font-size: 1.5rem;">BENGKEL ASYRAF</h1>
            </a>
            <button type="button" class="navbar-toggler me-0" data-bs-toggle="collapse"
                data-bs-target="#navbarCollapse">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <div class="navbar-nav">
                    <a href="{{ route('home') }}" class="nav-item nav-link {{ Request::is('/') ? 'active' : '' }}">Beranda</a>
                    <a href="{{ route('catalog.index') }}" class="nav-item nav-link {{ Request::is('catalog*') ? 'active' : '' }}">Katalog</a>
                    <a href="{{ route('orders.track') }}" class="nav-item nav-link {{ Request::is('track*') ? 'active' : '' }}">Lacak Pesanan</a>

                    @auth
                        <a href="{{ route('customer.orders.index') }}" class="nav-item nav-link {{ Request::is('customer/orders*') ? 'active' : '' }}">Pesanan Saya</a>
                        @if(Auth::user()->role === 'admin')
                            <a href="{{ route('admin.dashboard') }}" class="nav-item nav-link">Dashboard Admin</a>
                        @endif
                    @endauth
                </div>

                <div class="ms-auto d-none d-lg-flex align-items-center">
                    @guest
                        <a href="{{ route('login') }}" class="btn btn-outline-primary me-2">Masuk</a>
                        <a href="{{ route('register') }}" class="btn btn-light">Daftar</a>
                    @else
                        <span class="me-3 text-dark"><i class="fa fa-user me-1"></i> {{ Auth::user()->name }}</span>
                        <form action="{{ route('logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-danger me-2">Logout</button>
                        </form>
                    @endguest
                    <a href="{{ route('customer.orders.create') }}" class="btn btn-primary py-2 px-3">Pesan Sekarang</a>
                </div>

                {{-- Mobile auth links --}}
                <div class="d-lg-none py-2">
                    @guest
                        <a href="{{ route('login') }}" class="nav-item nav-link">Masuk</a>
                        <a href="{{ route('register') }}" class="nav-item nav-link">Daftar</a>
                    @else
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="nav-item nav-link btn btn-link text-start p-0 ps-2 pb-2">Logout</button>
                        </form>
                    @endguest
                    <a href="{{ route('customer.orders.create') }}" class="btn btn-primary w-100 py-2">Pesan Sekarang</a>
                </div>
            </div>
        </nav>
    </div>
</div>

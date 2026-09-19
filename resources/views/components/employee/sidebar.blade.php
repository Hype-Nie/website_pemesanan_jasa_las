<div class="employee-sidebar">
    <div class="sidebar-brand">
        <div class="d-flex align-items-center mb-2">
            <img src="{{ asset('img/logo.jpeg') }}" alt="Logo Bengkel Asyraf" style="height: 34px; width: 34px; object-fit: cover; border-radius: 6px;" class="me-2 shadow-sm">
            <div>
                <h4 class="mb-0 text-white fw-bold" style="font-size: 1.05rem; letter-spacing: 0.5px;">BENGKEL ASYRAF</h4>
            </div>
        </div>
        <div class="d-flex align-items-center justify-content-between mt-1">
            <span class="badge bg-warning text-dark px-2 py-1">
                <i class="fas fa-hard-hat me-1"></i> Panel Karyawan
            </span>
            <small class="text-white-50" style="font-size: 0.75rem;">Tim Teknisi</small>
        </div>
    </div>

    <nav class="mt-3">
        <div class="px-3 py-1 text-uppercase text-white-50" style="font-size: 0.7rem; letter-spacing: 1px; font-weight: 600;">
            Menu Kerja
        </div>

        <a href="{{ route('employee.dashboard') }}" class="nav-link {{ Request::is('employee/dashboard*') ? 'active' : '' }}">
            <i class="fas fa-tachometer-alt"></i> Dashboard Kerja
        </a>
        <a href="{{ route('employee.orders.index') }}" class="nav-link {{ Request::is('employee/orders*') ? 'active' : '' }}">
            <i class="fas fa-clipboard-list"></i> Semua Antrean
        </a>

        <hr class="border-secondary mx-3 my-3 opacity-25">

        <a href="{{ route('home') }}" class="nav-link" target="_blank">
            <i class="fas fa-external-link-alt"></i> Lihat Website Utama
        </a>
    </nav>
</div>

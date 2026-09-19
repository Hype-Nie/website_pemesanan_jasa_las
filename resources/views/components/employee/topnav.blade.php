<div class="employee-topnav d-flex justify-content-between align-items-center">
    <div class="d-flex align-items-center">
        <button class="btn btn-sm btn-outline-secondary d-lg-none me-2" id="employeeSidebarToggle" type="button" aria-label="Toggle Sidebar">
            <i class="fas fa-bars"></i>
        </button>
        <div>
            <h5 class="mb-0 fw-bold text-dark text-uppercase" style="font-size: 1.05rem;">
                @yield('page-title', 'Dashboard Karyawan')
            </h5>
            <small class="text-muted d-none d-md-inline">Bengkel Las Asyraf - Talaga, Bone</small>
        </div>
    </div>

    <div class="d-flex align-items-center">
        <div class="d-none d-sm-flex align-items-center me-3 border-end pe-3">
            <div class="rounded-circle bg-warning text-dark d-flex align-items-center justify-content-center me-2" style="width: 34px; height: 34px;">
                <i class="fas fa-user-cog"></i>
            </div>
            <div>
                <span class="fw-bold d-block text-dark small">{{ Auth::user()->name }}</span>
                <span class="badge bg-secondary" style="font-size: 0.65rem;">Teknisi Las</span>
            </div>
        </div>

        <a href="{{ route('password.change') }}" class="btn btn-sm btn-outline-secondary me-2" title="Ganti Kata Sandi">
            <i class="fas fa-key me-1"></i> <span class="d-none d-md-inline">Ganti Password</span>
        </a>

        <form action="{{ route('logout') }}" method="POST" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-sm btn-outline-danger" title="Keluar">
                <i class="fas fa-sign-out-alt me-1"></i> Logout
            </button>
        </form>
    </div>
</div>

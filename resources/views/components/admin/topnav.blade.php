<div class="admin-topnav d-flex justify-content-between align-items-center">
    <div>
        <button class="btn btn-sm btn-outline-secondary d-lg-none" id="sidebarToggle">
            <i class="fas fa-bars"></i>
        </button>
        <span class="ms-2 fw-bold text-uppercase">@yield('page-title', 'Dashboard')</span>
    </div>
    <div class="d-flex align-items-center">
        <span class="me-3 text-muted"><i class="fas fa-user-shield me-1"></i> {{ Auth::user()->name ?? 'Admin' }}</span>
        <form action="{{ route('logout') }}" method="POST" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-sm btn-outline-danger">
                <i class="fas fa-sign-out-alt me-1"></i> Logout
            </button>
        </form>
    </div>
</div>
